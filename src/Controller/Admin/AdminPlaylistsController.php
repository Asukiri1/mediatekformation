<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPlaylistsController extends AbstractController
{
    private $playlistRepository;
    private $categorieRepository;
    
    public function __construct(PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche la liste des playlists (Back-office)
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        
        return $this->render('admin/admin_playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Supprime une playlist (uniquement si elle est vide)
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->get('_token'))) {
            
            if (count($playlist->getFormations()) > 0) {
                $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette playlist car elle contient des formations.');
            } else {
                $this->playlistRepository->remove($playlist);
                $this->addFlash('success', 'La playlist a été supprimée avec succès');
            }
        }

        return $this->redirectToRoute('admin.playlists');
    }
    
    /**
     * Ajoute une nouvelle playlist
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/admin_playlists/formplaylist.html.twig', [
            'formplaylist' => $form->createView()
        ]);
    }
    
    /**
     * Modifie une playlist existante 
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $form = $this->createForm(PlaylistType::class, $playlist);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/admin_playlists/formplaylist.html.twig', [
            'formplaylist' => $form->createView(),
            'playlist' => $playlist
        ]);
    }

}