<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/admin/categories', name: 'admin.categorie.')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorie): Response
    {
        return $this->render('Admin/categories/index.html.twig', [
            'categories' => $categorie->findAll(),
        ]);
    }

    #[Route('/add', name: 'new')]
    function AddCategorie(Request $request, EntityManagerInterface $em) : Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie ajoutée avec succès');
            return $this->redirectToRoute('admin.categorie.index');
        }

        return $this->render('Admin/categories/edit.html.twig', 
        [
            "form" => $form,
        ]);   
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements:["id" => Requirement::DIGITS])]
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée avec succès');
            return $this->redirectToRoute('admin.categorie.index');
        }

        return $this->render('Admin/categories/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'], requirements:["id" => Requirement::DIGITS])]
    public function delete(Categorie $categorie, EntityManagerInterface $em): Response
    {
        $em->remove($categorie);
        $em->flush();
        $this->addFlash('success', 'Catégorie supprimée avec succès');
        return $this->redirectToRoute('admin.categorie.index');
    }


}
