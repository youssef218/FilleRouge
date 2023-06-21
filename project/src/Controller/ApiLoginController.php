<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'], options: ["expose" => true])]
    public function login(Security $security): JsonResponse
    {
        $user = $security->getUser();
        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED, [
                'Access-Control-Allow-Origin' => 'http://localhost:3000',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }
        
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'user' => $user->getUserIdentifier(),
            'role' => $user->getRoles(),
        ], Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => 'http://localhost:3000',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
    }


    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout()
    {
     return new JsonResponse([
        'message' => 'You are logged out',
        ]);
    }
}
