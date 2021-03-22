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
        // dd($panier->get());

        $date = new DateTime();
        $totalCommande = null;

        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setCreateAt($date);
        $commande->setIsPaid(false);

        foreach ($panier->get() as $ref => $quantity) {
            // dd($ref, $quantity);

            $prixBd = $this->entityManager->getRepository(Bd::class)->findOneByRef($ref)->getPrixPublic();

            $comDetails = new CommandeDetails();
            $comDetails->setCommande($commande);
            $comDetails->setBd($ref);
            $comDetails->setQuantity($quantity);
            $comDetails->setPrix($prixBd);
            $comDetails->setTotal($prixBd * $quantity);

            $this->entityManager->persist($comDetails);

            $totalCommande = $totalCommande + $comDetails->getTotal();
        }

        $commande->setTotal($totalCommande);

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        return $this->render('commande/index.html.twig', []);
    }
}
