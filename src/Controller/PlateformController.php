<?php

namespace App\Controller;

use App\Entity\Plateform;
use App\Repository\PlateformRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PlateformType;

/**
 * @Route("/plateform", name="plateform_")
 */
class PlateformController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PlateformRepository $plateformRepository): Response
    {
        return $this->render('plateform/index.html.twig', [
            'plateforms' => $plateformRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plateform = new Plateform();
        $form = $this->createForm(PlateformType::class, $plateform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plateform);
            $entityManager->flush();

            $this->addFlash('success', 'The new plateform has been created');

            return $this->redirectToRoute('plateform_index');
        }

        return $this->render('plateform/new.html.twig', [
            'plateform' => $plateform,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Plateform $plateform): Response
    {
        if (!$plateform) {
            throw $this->createNotFoundException(
                'No program with id : '.$plateform.' found in program\'s table.'
            );
        }
        return $this->render('plateform/show.html.twig', [
            'plateform' => $plateform,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plateform $plateform): Response
    {
        $form = $this->createForm(PlateformType::class, $plateform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The plateform has been edit');


            return $this->redirectToRoute('plateform_index');
        }

        return $this->render('plateform/edit.html.twig', [
            'plateform' => $plateform,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plateform $plateform): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plateform->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plateform);
            $entityManager->flush();

            $this->addFlash('danger', 'Plateform delete 🙊 !');
        }

        return $this->redirectToRoute('plateform_index');
    }
}
