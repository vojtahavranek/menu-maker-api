<?php declare(strict_types=1);

namespace MenuMaker\Security;

use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use MenuMaker\Controller\Exception\AuthFailureException;
use MenuMaker\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $tokenTTL = '1 month';

    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return null;
        }

        // if a User object, checkCredentials() is called
        return $this->entityManager->getRepository(User::class)
            ->findOneBy(['apiToken' => $apiToken]);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @throws \MenuMaker\Controller\Exception\AuthFailureException
     */
    public function authenticateByFormCredentials(Request $request): string
    {
        if (($clientId = $request->get('client_id')) === null) {
            throw new AuthFailureException();
        }

        if (($clientSecret = $request->get('client_secret')) === null) {
            throw new AuthFailureException();
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $clientId]);

        if (!$this->passwordEncoder->isPasswordValid($user, $clientSecret)) {
            throw new AuthFailureException();
        }

        return $this->refreshToken($user);
    }

    /**
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return bin2hex(random_bytes(64));
    }

    /**
     * @return string
     * @throws \MenuMaker\Controller\Exception\AuthFailureException
     */
    public function refreshToken(User $user): string
    {
        $expireDate = new \DateTimeImmutable();
        $expireDate = $expireDate->add(new DateInterval('P7D'));

        if ($user->getApiTokenExpireDate() < new \DateTimeImmutable()) {
            try {
                $token = $this->generateToken();
            } catch (\Exception $e) {
                throw new AuthFailureException('Couldn\'t generate token. Please try again!', 500);
            }

            $user->setApiToken($token);
            $user->setApiTokenExpireDate($expireDate);
            $this->entityManager->flush();

            return $token;
        }

        $user->setApiTokenExpireDate($expireDate);
        $this->entityManager->flush();

        return $user->getApiToken();
    }
}
