<?php

namespace App\Controller;

use App\Entity\Bd;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BdController extends AbstractController
{
    private $entityManager;
    private $filesystem;

    public function __construct(EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;
        $this->filesystem = $filesystem;
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
            // retourne True si le fichier/la couverture de bd (.jpg) existe dans le dossier "../public/couv"
            $bool = $this->filesystem->exists('../public/couv/' . $bds[$i]->getImage());
            // Si $bool == False, on change "image" par "defaut.jpg"
            if ($bool == false) {
                $bds[$i]->setImage("defaut.jpg");
            }
            $bdArray[] = $bds[$i];
        }

        // dd($bdArray);

        return $this->render('bd/index.html.twig', [
            // 'bds' => $bds,
            'bds' => $bdArray,
        ]);
    }
}
