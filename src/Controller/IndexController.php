<?php

declare(strict_types=1);

namespace App\Controller;

use Safe\Exceptions\JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    /**
     * @throws JsonException
     *
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|logout|refresh|_(profiler|wdt)).*"}, name="index", methods={"GET"})
     */
    public function indexAction(string $vueRouting): Response
    {
        return $this->render('index/index.html.twig');
    }
}
