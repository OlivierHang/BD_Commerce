<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    // Step 4a
    // Doctrine (ORM Symfony), et à travers son "manager" (ou Entity Manager), permet la manipulations des data de la bd
    private $entityManager;

    // l'Entity manager est défini quand le Controller est appelé
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    // Step 1a
    // injection de dépendance => HttpFoundation\Request
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // Si user est déjà connecté, redirigé vers la page "compte"
        if ($this->getUser()) {
            return $this->redirectToRoute('compte');
        }

        // Step 1b
        // Ecouter la requête entrante (objet Request de Symfony) pour voir s'il n'y a pas un Post
        $form->handleRequest($request);

        // Step 2
        // Si le form est soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Step 3
            // Recupère les data du form et mettre ça dans la variable $user
            $user = $form->getData();

            // Encodage du mot de passe
            $password = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            // Step 4b
            // // Fige la data pour pouvoir l'enregistrer
            $this->entityManager->persist($user);
            // // Execute la persistance / Enregistre la data dans la bd
            $this->entityManager->flush();

            return $this->redirectToRoute("compte");
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
