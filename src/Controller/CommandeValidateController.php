<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="commande_validate")
     */
    public function index($stripeSessionId): Response
    {
        $commande = $this->entityManager->getRepository(Commande::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$commande) {
            return $this->redirectToRoute('home');
        }

        $commande->setIsPaid(true);
        $this->entityManager->flush();

        return $this->redirectToRoute('commande_detail', ['reference' => $commande->getReference()]);
    }
}
