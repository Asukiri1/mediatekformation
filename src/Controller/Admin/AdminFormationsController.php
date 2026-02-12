<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController
{
    /**
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        
        return $this->render('admin/admin_formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    #[Route('/admin/formations/suppr/{id}', name: 'admin.formation.suppr')]
    public function suppr(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->get('_token'))) {
            $this->formationRepository->remove($formation);
            $this->addFlash('success', 'La formation a été supprimée avec succès');
        }

        return $this->redirectToRoute('admin.formations');
    }
       
    #[Route('/admin/formations/ajout', name: 'admin.formation.ajout')]
    public function ajout(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/admin_formations/formformation.html.twig', [
            'formformation' => $form->createView()
        ]);
    }

    #[Route('/admin/formations/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/admin_formations/formformation.html.twig', [
            'formformation' => $form->createView()
        ]);
    }
}
    
