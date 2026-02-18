<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    private $categorieRepository;
    
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche la liste et gère l'ajout d'une catégorie
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $existing = $this->categorieRepository->findOneBy(['name' => $categorie->getName()]);
            
            if ($existing) {
                $this->addFlash('danger', 'Cette catégorie existe déjà.');
            } else {
                $this->categorieRepository->add($categorie);
                $this->addFlash('success', 'Catégorie ajoutée avec succès.');
                return $this->redirectToRoute('admin.categories');
            }
        }

        $categories = $this->categorieRepository->findAll();
        
        return $this->render('admin/admin_categories/index.html.twig', [
            'categories' => $categories,
            'formcategorie' => $form->createView()
        ]);
    }

    /**
     * Supprime une catégorie si elle n'est pas utilisée
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id, Request $request): Response
    {
        $categorie = $this->categorieRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->get('_token'))) {
            if (count($categorie->getFormations()) > 0) {
                $this->addFlash('danger', 'Impossible de supprimer cette catégorie car elle est utilisée dans des formations.');
            } else {
                $this->categorieRepository->remove($categorie);
                $this->addFlash('success', 'Catégorie supprimée.');
            }
        }

        return $this->redirectToRoute('admin.categories');
    }
}