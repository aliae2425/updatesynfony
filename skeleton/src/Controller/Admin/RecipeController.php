<?php

namespace App\Controller\Admin;

use App\Entity\Recette;
use App\Form\RecipeType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path:"/admin/recette", name:"admin.recipe.")]
class RecipeController extends AbstractController
{
    #[Route(path:"/", name:"index")]
    public function index(RecetteRepository $recetteRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        // dd($recetteRepository->findAll());
        $page = $request->query->get('page', 1);
        $LIMITE = 1;
        $recette = $recetteRepository->paginateRecipies($page);
        return $this->render("admin/recipe/index.html.twig", [
            // 'recettes' =>  $recetteRepository->findAllWithCategory()
            'recettes' =>  $recette, 
        ]);
    }

    #[Route(path:'/add', name:'new')]
    public function AddRecette(Request $request, EntityManagerInterface $em)
    {
        $recette = new Recette();
        $form = $this->createForm(RecipeType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recette);
            $em->flush();
            $this->addFlash('success', 'Recette ajoutée avec succès');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render("admin/recipe/edit.html.twig", ["form"=> $form]);

    }

    #[Route(path:'/{id}/edit', name:'edit', methods:["GET" , "POST"], requirements: ["id" => Requirement::DIGITS])]
    public function EditRecette(Recette $recette, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Recette modifiée avec succès');
            return $this->redirectToRoute('admin.recipe.index');
        }

       return $this->render("admin/recipe/edit.html.twig", [
        "recette" => $recette,
         "form"=> $form
        ]);
    }

    #[Route(path:'/{id}/delete', name:'delete', requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function DeleteRecette(Recette $recette, EntityManagerInterface $em)
    {
        $em->remove($recette);
        $em->flush();
        $this->addFlash('success', 'Recette supprimée avec succès');
        return $this->redirectToRoute('admin.recipe.index');
    }

}
