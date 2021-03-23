<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Bd;
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
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(Panier $panier): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        // dd($panier->get());

        foreach ($panier->get() as $ref => $quantity) {

            $prixBd = $this->entityManager->getRepository(Bd::class)->findOneByRef($ref)->getPrixPublic();
            $titreBd = $this->entityManager->getRepository(Bd::class)->findOneByRef($ref)->getTitre();

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($prixBd * 100),
                    'product_data' => [
                        'name' => $titreBd,
                        'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => $quantity,
            ];
        }

        $panier->remove();

        // dd($product_for_stripe);

        Stripe::setApiKey('sk_test_51IY7XADH5SafTNx86tPOfyQMqrwA0WT2KQYr6zXVjWVhSqoxFKzLJb3ImT9NYvQ2EIorMFRRWR3MdmDIzY5Xqx9N008digkjYt');

        // header('Content-Type: application/json');

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);


        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
