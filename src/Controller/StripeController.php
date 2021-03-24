<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Bd;
use App\Entity\Commande;
use App\Entity\CommandeDetails;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index($reference): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $commande = $this->entityManager->getRepository(Commande::class)->findOneByReference($reference);
        $comDetails = $this->entityManager->getRepository(CommandeDetails::class)->findByCommande($commande);


        foreach ($comDetails as $com) {

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($com->getPrix() * 100),
                    'product_data' => [
                        'name' => $com->getBd(),
                        'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => $com->getQuantity(),
            ];
        }

        Stripe::setApiKey('sk_test_51IY7XADH5SafTNx86tPOfyQMqrwA0WT2KQYr6zXVjWVhSqoxFKzLJb3ImT9NYvQ2EIorMFRRWR3MdmDIzY5Xqx9N008digkjYt');

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $commande->setStripeSessionId($checkout_session->id);
        $this->entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
