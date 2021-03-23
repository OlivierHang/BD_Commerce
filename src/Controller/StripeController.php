<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/stripe", name="stripe")
     */
    public function index(): Response
    {
        Stripe::setApiKey("sk_test_51IY7XADH5SafTNx86tPOfyQMqrwA0WT2KQYr6zXVjWVhSqoxFKzLJb3ImT9NYvQ2EIorMFRRWR3MdmDIzY5Xqx9N008digkjYt  ");

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => 2000,
                        'product_data' => [
                            'name' => 'Stubborn Attachments',
                            'images' => ["https://i.imgur.com/EHyR2nP.png"],
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        // echo json_encode(['id' => $checkout_session->id]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
