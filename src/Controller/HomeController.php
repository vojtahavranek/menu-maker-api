<?php declare(strict_types=1);

namespace MenuMaker\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('<h1>Welcome to MenuMaker API!</h1>');
    }
}
