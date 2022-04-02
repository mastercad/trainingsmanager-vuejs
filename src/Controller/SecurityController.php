<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Users;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class SecurityController extends AbstractController
{
    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /** @var JWTTokenManagerInterface */
    private JWTTokenManagerInterface $jWTManager;

    public function __construct(SerializerInterface $serializer, JWTTokenManagerInterface $jWTManager)
    {
        $this->serializer = $serializer;
        $this->jWTManager = $jWTManager;
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function loginAction(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof Users) {
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

    /**
     * @Route("/api/refresh", name="api_refresh", methods={"POST"})
     */
    public function refreshAction(): JsonResponse
    {
      $user = $this->getUser();

      if (!$user instanceof Users) {
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

    /**
     * @throws RuntimeException
     *
     * @Route("/logout", name="api_logout")
     */
    public function logoutAction(): void
    {
        throw new RuntimeException('This should not be reached!');
    }
}
