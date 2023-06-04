<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $jsonData = json_decode($request->getContent(), true);

        $form->submit($jsonData);

        if ($form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
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

        return new JsonResponse(json_encode(['success' => false, 'errors' => $this->serializer->serialize($form, JsonEncoder::FORMAT)]), Response::HTTP_OK, [], true);
    }
}
