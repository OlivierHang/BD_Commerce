<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Bd;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    private $entityManager;
    private $filesystem;

    public function __construct(EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;
        $this->filesystem = $filesystem;
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function index(Panier $panier): Response
    {
        // dd($panier->get());

        $panierComplet = [];

        if (!empty($panier->get())) {
            foreach ($panier->get() as $id => $quantite) {

                $bd = $this->entityManager->getRepository(Bd::class)->findOneByRef($id);
                // retourne True si le fichier/la couverture de bd (.jpg) existe dans le dossier "../public/couv"
                $bool = $this->filesystem->exists('../public/couv/' . $bd->getImage());
                // Si $bool == False, on change "image" par "defaut.jpg"
                if ($bool == false) {
                    $bd->setImage("defaut.jpg");
                }

                $panierComplet[] = [
                    'bd' => $bd,
                    'quantite' => $quantite,
                ];
            }

            return $this->render('panier/index.html.twig', [
                'panier' => $panierComplet,
            ]);
        }

        return $this->render('panier/index.html.twig', []);
    }

    /**
     * @Route("/panier/add/{ref}", name="add_to_panier")
     */
    public function add(Panier $panier, $ref): Response
    {
        $panier->add($ref);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/remove", name="remove_panier")
     */
    public function remove(Panier $panier): Response
    {
        $panier->remove();

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/delete/{ref}", name="delete_from_panier")
     */
    public function delete(Panier $panier, $ref): Response
    {
        $panier->delete($ref);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/decrease/{ref}", name="decrease_from_panier")
     */
    public function decrease(Panier $panier, $ref): Response
    {
        $panier->decrease($ref);

        return $this->redirectToRoute('panier');
    }
}
