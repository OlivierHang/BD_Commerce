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
     * @Route("/bd", name="bds")
     */
    public function index(): Response
    {
        $bds = $this->entityManager->getRepository(Bd::class)->findAll();
        $bdArray = [];

        // foreach ($bds as $bd) {
        //     $bdArray[] = $bd;
        // }

        for ($i = 0; $i < 12; $i++) {
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

    /**
     * @Route("/bd/{ref}", name="bd")
     */
    public function show($ref): Response
    {
        // Va chercher une "BD" avec la "ref" passer en url
        $bd = $this->entityManager->getRepository(Bd::class)->findOneByRef($ref);

        // Si y a pas de BD avec la "ref", redirection vers page "bds" (page avec toutes les bd)
        if (!$bd) {
            return $this->redirectToRoute('bds');
        }

        // dd($bd);

        // retourne True si le fichier/la couverture de bd (.jpg) existe dans le dossier "../public/couv"
        $bool = $this->filesystem->exists('../public/couv/' . $bd->getImage());
        // Si $bool == False, on change "image" par "defaut.jpg"
        if ($bool == false) {
            $bd->setImage("defaut.jpg");
        }


        return $this->render('bd/bd_detail.html.twig', [
            'bd' => $bd,
        ]);
    }
}
