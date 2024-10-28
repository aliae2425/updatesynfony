<?php 

namespace App\Controller\API;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route(path:"/api/me", name: "api.me", methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function me(){
        return $this->json($this->getUser());
    }

    #[Route(path:"/Auth/token", name: "api.login", methods: ["GET"])]
    public function login(){
        
        return $this->json( ["api_token" => $this->getUser()->getApiToken()], Response::HTTP_OK);
    }
}