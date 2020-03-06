<?php declare(strict_types=1);

namespace MenuMaker\Controller;

use Doctrine\ORM\EntityManagerInterface;
use MenuMaker\Controller\Exception\AuthFailureException;
use MenuMaker\Controller\Exception\RegisterFailureException;
use MenuMaker\Entity\User;
use MenuMaker\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $tokenAuthenticator;

    public function __construct(TokenAuthenticator $tokenAuthenticator)
    {
        $this->tokenAuthenticator = $tokenAuthenticator;
    }

    /**
     * @Route("/api/authorize", methods={"POST"})
     */
    public function authorizeAction(Request $request): Response
    {
        $data = json_decode($request->getContent());

        try {
            $token = $this->tokenAuthenticator->authenticateByJSONCredentials($data);
        } catch (AuthFailureException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['token' => $token]);
    }

    /**
     * @Route("/api/register", methods={"POST"})
     */
    public function registerAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ): Response {
        try {
            $token = $this->registerUser($request, $passwordEncoder, $entityManager);
        } catch (RegisterFailureException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 200);
        } catch (AuthFailureException $e) {
            return new JsonResponse(['message' => 'You have been registered. Please, log in now.'], 200);
        }

        return new JsonResponse(['token' => $token]);
    }

    /**
     * @throws \MenuMaker\Controller\Exception\RegisterFailureException
     * @throws \MenuMaker\Controller\Exception\AuthFailureException
     */
    private function registerUser(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ): string {
        // TODO: validate e-mail
        if (($email = $request->get('email')) === null) {
            throw new RegisterFailureException('Parameter e-mail is missing.');
        }

        if ($entityManager->getRepository(User::class)->findOneBy(['email' => $email]) !== null) {
            throw new RegisterFailureException('User with this e-mail is already registered. Please, log in.');
        }

        if (($pass = $request->get('pass')) === null) {
            throw new RegisterFailureException('Parameter password is missing.');
        }

        $newUser = new User($email);
        $encodedPass = $passwordEncoder->encodePassword($newUser, $pass);
        $newUser->setPassword($encodedPass);

        $entityManager->persist($newUser);
        $entityManager->flush();

        // TODO: i should confirm that user - at least, give him no rights
        return $this->tokenAuthenticator->refreshToken($newUser);
    }
}
