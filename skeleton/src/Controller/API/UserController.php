<?php 

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route(path:"/api/me", name: "api.me", methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function me(){
        return $this->json($this->getUser());
    }
}