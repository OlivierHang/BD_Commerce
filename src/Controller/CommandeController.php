<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Bd;
use App\Entity\Commande;
use App\Entity\CommandeDetails;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="commande")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $commandes = $this->entityManager->getRepository(Commande::class)->findByUser($user);

        if (!empty($commandes)) {
            $comArray = [];
            foreach ($commandes as $com) {
                // dump($com);
                // dd($commandes);
                if ($com->getIsPaid() == true) {
                    $comArray[] = $com;
                }
            }

            // Si le user n'a pas de commande "payé", on ne lui affiche pas de commande
            if (empty($comArray)) {
                return $this->render('commande/index.html.twig', []);
            }

            return $this->render('commande/index.html.twig', [
                'commandes' => $comArray,
            ]);
        }

        return $this->render('commande/index.html.twig', []);
    }

    /**
     * @Route("/commande/detail/{reference}", name="commande_detail")
     */
    public function show($reference): Response
    {
        $commande = $this->entityManager->getRepository(Commande::class)->findOneByReference($reference);
        $comDetails = $this->entityManager->getRepository(CommandeDetails::class)->findByCommande($commande);

        // dd($commande);

        return $this->render('commande/detail.html.twig', [
            'commande' => $commande,
            'details' => $comDetails,
        ]);
    }

    /**
     * @Route("/commande/ajout", name="commande_add")
     */
    public function add(Panier $panier): Response
    {
        // Si il y a un panier, il le sauvegarde en BDD
        if (!empty($panier->get())) {

            // preparation de l'enregistrement de la commande en BDD
            $date = new DateTime();
            $reference = $date->format('dmY') . '-' . uniqid();
            $totalCommande = null;

            $commande = new Commande();
            $commande->setUser($this->getUser());
            $commande->setReference($reference);
            $commande->setCreateAt($date);
            $commande->setIsPaid(false);


            foreach ($panier->get() as $ref => $quantity) {
                // preparation de l'enregistrement de la commandeDetails en BDD
                $prixBd = $this->entityManager->getRepository(Bd::class)->findOneByRef($ref)->getPrixPublic();

                $comDetails = new CommandeDetails();
                $comDetails->setCommande($commande);
                $comDetails->setBd($ref);
                $comDetails->setQuantity($quantity);
                $comDetails->setPrix($prixBd);
                $comDetails->setTotal($prixBd * $quantity);

                // commandeDetails persisté
                $this->entityManager->persist($comDetails);

                $totalCommande = $totalCommande + $comDetails->getTotal();
            }

            $commande->setTotal($totalCommande);

            // commande persisté
            $this->entityManager->persist($commande);
            // Ajout dans la bdd
            $this->entityManager->flush();
            // Enleve le panier
            $panier->remove();

            return $this->redirectToRoute('commande_detail', ['reference' => $reference]);
        } else {
            return $this->redirectToRoute('panier');
        }
    }
}
