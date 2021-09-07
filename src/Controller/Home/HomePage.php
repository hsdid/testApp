<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class homePage
 * @Route("/", name="home_page")
 */
class HomePage extends AbstractController
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render('/index.html.twig');
    }
}
