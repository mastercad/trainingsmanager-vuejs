<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Users;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Safe\Exceptions\JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use function Safe\json_encode;

final class IndexController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var JWTTokenManagerInterface */
    private JWTTokenManagerInterface $jWTManager;

    public function __construct(SerializerInterface $serializer, JWTTokenManagerInterface $jWTManager)
    {
        $this->serializer = $serializer;
        $this->jWTManager = $jWTManager;
    }

    /**
     * @throws JsonException
     *
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|logout|login|_(profiler|wdt)).*"}, name="index")
     */
    public function indexAction(): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $data = null;
        $token = null;

        if ($user instanceof Users) {
            $userClone = clone $user;
            $userClone->setPassword('');
            $data = $this->serializer->serialize($userClone, JsonEncoder::FORMAT);
            $token = $this->jWTManager->create($user);
        }

        return $this->render('index/index.html.twig', [
            'isAuthenticated' => json_encode(!empty($user)),
            'user' => $data ?? json_encode($data),
            'token' => $token
        ]);
    }
}
