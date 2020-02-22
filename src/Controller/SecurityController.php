<?php declare(strict_types=1);

namespace MenuMaker\Controller;

use MenuMaker\Controller\Exception\AuthFailureException;
use MenuMaker\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/authorize", methods={"POST"})
     */
    public function authorizeAction(Request $request, TokenAuthenticator $tokenAuthenticator): Response
    {
        try {
            $token = $tokenAuthenticator->authenticateByFormCredentials($request);
        }
        catch (AuthFailureException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['token' => $token]);
    }
}
