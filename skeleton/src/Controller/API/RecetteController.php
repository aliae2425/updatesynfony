<?php 

namespace App\Controller\API;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recette', name: 'api.recette.')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecetteRepository $recetteRepository)
    {
        $recettes = $recetteRepository->findAll();
        
    }
}