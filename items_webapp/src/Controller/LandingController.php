<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * LandingController
 */
class LandingController extends AbstractController
{
    /**
     * landing
     *
     * @return Response
     */
    #[Route(
        path: ['/', '/home'],
        name: 'app_landing',
        methods: ['GET']
    )]
    public function landing(): Response
    {
        $title = 'What are you using?';
        $msg = '✨ This is a management software developed using the Symfony framework! ✨';

        return $this->render('landing.html.twig', [
            'title' => $title,
            'msg' => $msg,
        ]);
    }
}
