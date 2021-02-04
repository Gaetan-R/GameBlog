<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyProfileController extends AbstractController
{
    /**
     * @Route("/my-account", name="my_account")
     */
    public function index(): Response
    {
        $userArticles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['owner' =>$this->getUser()]);

        return $this->render('security/My_account.html.twig', [
                'userArticles' => $userArticles            ]
        );
    }

}
