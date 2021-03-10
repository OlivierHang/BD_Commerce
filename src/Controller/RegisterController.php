<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    // C'est grace au "manager" de Doctrine qu'on peut faire nos manipulation avec la bd
    private $entityManager;

    // l'Entity manager est défini quand le Controller est appelé
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register")
     */
    // injection de dépendance => HttpFoundation\Request
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // Step 1
        // Permet d'écouter la requête entrante (objet Request de Symfony) pour voir s'il n'y a pas un Post
        $form->handleRequest($request);

        // Step 2
        // Si le form est soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Step 3
            // Recupère les data du form et mettre ça dans la variable $user
            $user = $form->getData();

            // // Doctrine (ORM Symfony) permet l'interaction avec la bd
            // $doctrine = $this->getDoctrine()->getManager();

            // Step 4
            // // Fige la data pour pouvoir l'enregistrer
            // $doctrine->persist($user);
            $this->entityManager->persist($user);
            // // Execute la persistance / Enregistre la data dans la bd
            // $doctrine->flush();
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
