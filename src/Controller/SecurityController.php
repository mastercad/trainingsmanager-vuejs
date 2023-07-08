<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Nonstandard\Uuid;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

use function assert;
use function json_decode;
use function json_encode;

final class SecurityController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private JWTTokenManagerInterface $jWTManager)
    {
    }

    #[Route(path: '/login', name: 'login', methods: ['POST'])]
    public function loginAction(): JsonResponse
    {
        $user = $this->getUser();
        assert($user instanceof Users);

        if (! $user instanceof Users) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN, []);
        }

        $userClone = clone $user;
        $userClone->setPassword('');
        $token = $this->jWTManager->create($userClone);

        $data['isAuthenticated'] = true;
        $data['user'] = $this->serializer->serialize($userClone, JsonEncoder::FORMAT);
        $data['token'] = $token;

        return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
    }

    #[Route(path: '/api/refresh', name: 'api_refresh', methods: ['POST'])]
    public function refreshAction(): JsonResponse
    {
        $user = $this->getUser();

        if (! $user instanceof Users) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN, []);
        }

        $userClone = clone $user;
        $userClone->setPassword('');
        $token = $this->jWTManager->create($userClone);

        $data['isAuthenticated'] = true;
        $data['user'] = $this->serializer->serialize($userClone, JsonEncoder::FORMAT);
        $data['token'] = $token;

        return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
    }

    /** @throws RuntimeException */
    #[Route(path: '/logout', name: 'api_logout')]
    public function logoutAction(): never
    {
    }

    #[Route(path: '/register', name: 'user_registration', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $jsonData = json_decode($request->getContent(), true);

        $form->submit($jsonData);

        if ($form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
            $user->setId(Uuid::uuid4());

            $entityManager->persist($user);
            $entityManager->flush();

            $userClone = clone $user;
            $userClone->setPassword('');
            $token = $this->jWTManager->create($userClone);

            $data['isAuthenticated'] = true;
            $data['user'] = $this->serializer->serialize($userClone, JsonEncoder::FORMAT);
            $data['token'] = $token;

            return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(
            json_encode(
                ['success' => false, 'errors' => $this->serializer->serialize($form, JsonEncoder::FORMAT)]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
