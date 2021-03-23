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
    public function index(Panier $panier): Response
    {
        // Si il y a un panier, il le sauvegarde en BDD
        if (!empty($panier)) {
            // dd($panier);
            // preparation de l'enregistrement de la commande en BDD
            $date = new DateTime();
            $totalCommande = null;

            $commande = new Commande();
            $commande->setUser($this->getUser());
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

            // Clear le panier après avoir mis la commande en BDD
            $panier->remove();
        }

        $user = $this->getUser();
        $commandes = $this->entityManager->getRepository(Commande::class)->findByUser($user);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/commande/{idCommande}", name="commande_detail")
     */
    public function show($idCommande): Response
    {
        $comDetails = $this->entityManager->getRepository(CommandeDetails::class)->findByCommande($idCommande);

        if (empty($comDetails)) {
            return $this->redirectToRoute('commande');
        }

        return $this->render('commande/detail.html.twig', [
            'commandes' => $comDetails,
        ]);
    }

    /**
     * @Route("/commande/paiement", name="pay_commande")
     */
    public function paiement(): Response
    {
        return $this->render('commande/index.html.twig', []);
    }
}
