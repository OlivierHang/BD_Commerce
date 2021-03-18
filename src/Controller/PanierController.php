<?php

namespace App\Controller;

use App\Classe\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Panier $panier): Response
    {
        // dd($panier->get());

        return $this->render('panier/index.html.twig', [
            'panier' => $panier->get()
        ]);
    }

    /**
     * @Route("/panier/add/{ref}", name="add_to_panier")
     */
    public function add(Panier $panier, $ref): Response
    {
        $panier->add($ref);

        return $this->redirectToRoute('panier');
        // return $this->render('panier/index.html.twig', []);
    }

    /**
     * @Route("/panier/remove", name="remove_panier")
     */
    public function remove(Panier $panier): Response
    {
        $panier->remove();

        return $this->redirectToRoute('panier');
        // return $this->render('panier/index.html.twig', []);
    }
}
