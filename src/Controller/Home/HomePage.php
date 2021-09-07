<?php


namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class homePage
 * @package App\Controller\Home
 * @Route("/", name="home_page")
 */
class HomePage extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('/index.html.twig');
    }
}
