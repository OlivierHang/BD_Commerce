<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Bd;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        // $bds -> objet avec toutes les BD
        $bds = $this->entityManager->getRepository(Bd::class)->findAll();
        $bdArray = [];

        // Creation du formulaire
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        // Traitement du filtre
        // Si un titre est recherché, les bd avec le titre correspondant sera affiché
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $search->titre != null) {
            $bds = $this->entityManager->getRepository(Bd::class)->findWithSearchTitre($search->titre);
        }

        // foreach ($bds as $bd) {
        //     $bdArray[] = $bd;
        // }

        // Si le nombre de bd est inférieur à 12 bd, il ne prendra que les 12 premières bd
        // Sinon il prendra les bd dispo dans $bds
        // (Pour le grid colonne de bootstrap)
        if (count($bds) >= 12) {
            for ($i = 0; $i < 12; $i++) {
                // retourne True si le fichier/la couverture de bd (.jpg) existe dans le dossier "../public/couv"
                $bool = $this->filesystem->exists('../public/couv/' . $bds[$i]->getImage());
                // Si $bool == False, on change "image" par "defaut.jpg"
                if ($bool == false) {
                    $bds[$i]->setImage("defaut.jpg");
                }
                $bdArray[] = $bds[$i];
            }
        } else {
            for ($i = 0; $i < count($bds); $i++) {
                // retourne True si le fichier/la couverture de bd (.jpg) existe dans le dossier "../public/couv"
                $bool = $this->filesystem->exists('../public/couv/' . $bds[$i]->getImage());
                // Si $bool == False, on change "image" par "defaut.jpg"
                if ($bool == false) {
                    $bds[$i]->setImage("defaut.jpg");
                }
                $bdArray[] = $bds[$i];
            }
        }

        return $this->render('bd/index.html.twig', [
            // 'bds' => $bds,
            'bds' => $bdArray,
            'form' => $form->createView(),
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
