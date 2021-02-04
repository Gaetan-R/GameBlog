<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @Route("/article", name="article_")
 */

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setOwner($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'The new article has been created');

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})use Doctrine\ORM\EntityManagerInterface;

     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route ("/{slug}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        if (!($this->getUser() == $article->getOwner())) {
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The article has been edit');


            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('danger', 'Article delete 🙊 !');

        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}/watchlist", name="watchlist")
     * @return Response
     */
    public function addToWatchlist(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()->getArticles()->contains($article)) {
            $this->getUser()->removeArticle($article);
        }
        else {
            $this->getUser()->addArticle($article);
        }

        $entityManager->flush();

        return $this->json([
            'isInWatchlist' => $this->getUser()->isInWatchlist($article)
        ]);
    }
}
