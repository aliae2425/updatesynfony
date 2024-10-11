<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{


    #[Route(path:"/recette", name:"recipe.index")]
    public function index(Request $request, RecetteRepository $recetteRepository): Response
    {
        $recette = $recetteRepository->findAll();
        return $this->render("recipe/index.html.twig", [
            'recettes' => $recette
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[a-z0-9\-]+', 'id' => '\d+'])]
    public function show(Request $request, RecetteRepository $recetteRepository, string $slug, int $id): Response
    {
        $recette = $recetteRepository->find($id);
        if ($recette->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'id' => $recette->getId(),
                'slug' => $recette->getSlug()
            ], 301);
        }
        return $this->render('recipe/show.html.twig', [
            'recette' => $recette
        ]);
    }

}
