<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article", name="article_")
 */

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);

    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article_index');
        }
        return $this->render('article/new.html.twig', [
            "form"  => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(int $id):Response

    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        return $this->render('article/show.html.twig', [

            'article' => $article,
        ]);
    }
}
