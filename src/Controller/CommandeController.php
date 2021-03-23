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

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/commande/details/{idCommande}", name="commande_detail")
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
