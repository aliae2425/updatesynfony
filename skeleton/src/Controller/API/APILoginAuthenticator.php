<?php 

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class APILoginAuthenticator extends AbstractAuthenticator
{
    
    public function __construct(
        private UserRepository $userRepository
    ){}

    public function supports(Request $request): ?bool
    {
        return
            $request->headers->has('authorization') && 
                str_contains($request->headers->get('authorization'), 'Basic ' ) 
            ;    
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->headers->get('PHP_AUTH_USER', '');
        $password = $request->headers->get('PHP_AUTH_PW', '');
        return new Passport( new  UserBadge($username), new PasswordCredentials($password));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['message' => $exception->getMessageData()], Response::HTTP_UNAUTHORIZED);
    }

}