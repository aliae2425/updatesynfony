<?php 

namespace App\Controller\API;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recette', name: 'api.recette.')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecetteRepository $recetteRepository, Request $request)
    {
        $recettes = $recetteRepository->paginateRecipies($request->query->getInt('page', 1));
        return $this->json($recettes, 200, [], 
        [
            'groups' => 'recette.index'
            ]);

    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Recette $recette)
    {
        return $this->json($recette, 200, [], 
        [
            'groups' => 'recette.show'
            ]);
    }

}