<?php

namespace App\Controller;

use App\Entity\Plateform;
use App\Form\PlateformType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/plateform", name="plateform_")
 */
class PlateformController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $plateforms = $this->getDoctrine()
            ->getRepository(Plateform::class)
            ->findAll();

        return $this->render('plateform/index.html.twig', [
            'plateforms' => $plateforms
        ]);

    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $plateform = new Plateform();
        $form = $this->createForm(PlateformType::class, $plateform);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plateform);
            $entityManager->flush();
            return $this->redirectToRoute('plateform_index');
        }
        return $this->render('plateform/new.html.twig',[
            "form" => $form->createView()
        ]);
    }
}
