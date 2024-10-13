<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecipeType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{


    #[Route(path:"/recette", name:"recipe.index")]
    public function index(Request $request, RecetteRepository $recetteRepository, EntityManagerInterface $em): Response
    {
        $recette = $recetteRepository->findAll();
        $recette[1]->setSlug('pate-bolo');
        $em->flush();
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

    #[Route(path:'/recette/filter/{duration}', name:'recipe.filter.duration', requirements: [ "duration" => "\d+" ])]
    public function indexFilter(Request $request, RecetteRepository $recetteRepository, int $duration): Response
    {
        $recette =  $recetteRepository->findWithDurationLowerThan($duration);
        return $this->render("recipe/index.html.twig", [
            'recettes' => $recette
        ]);

    }

    #[Route(path:'/recette/{id}/edit', name:'recipe.edit', requirements: ["id" => "\d+"])]
    public function editRecette(Recette $recette, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Recette modifiée avec succès');
            return $this->redirectToRoute('recipe.index');
        }

       return $this->render("recipe/edit.html.twig", ["recette" => $recette, "form"=> $form]);
    }

    #[Route(path:'/recette/ajout', name:'recipe.new')]
    public function newRecette(Request $request, EntityManagerInterface $em)
    {
        $recette = new Recette();
        $form = $this->createForm(RecipeType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recette);
            $em->flush();
            $this->addFlash('success', 'Recette ajoutée avec succès');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render("recipe/edit.html.twig", ["form"=> $form]);
    }

    #[Route(path:'/recette/{id}/delete', name:'recipe.delete', requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function deleteRecette(Recette $recette, EntityManagerInterface $em)
    {
        $em->remove($recette);
        $em->flush();
        $this->addFlash('success', 'Recette supprimée avec succès');
        return $this->redirectToRoute('recipe.index');
    }


}
