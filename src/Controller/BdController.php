<?php

namespace App\Controller;

use App\Entity\Bd;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BdController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/bd", name="bd")
     */
    public function index(): Response
    {
        $bds = $this->entityManager->getRepository(Bd::class)->findAll();
        $bdArray = [];

        // foreach ($bds as $bd) {
        //     $bdArray[] = $bd;
        // }

        for ($i = 0; $i < 5; $i++) {
            $bdArray[] = $bds[$i];
        }

        // dd($bdArray);


        return $this->render('bd/index.html.twig', [
            // 'bds' => $bds,
            'bds' => $bdArray,
        ]);
    }
}
