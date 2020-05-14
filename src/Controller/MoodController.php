<?php

namespace App\Controller;

use App\Entity\Mood;
use App\Form\MoodType;
use App\Repository\MoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mood")
 */
class MoodController extends AbstractController
{
    /**
     * @Route("/", name="mood_index", methods={"GET"})
     */
    public function index(MoodRepository $moodRepository): Response
    {
        return $this->render('mood/index.html.twig', [
            'moods' => $moodRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mood_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mood = new Mood();
        $form = $this->createForm(MoodType::class, $mood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mood);
            $entityManager->flush();

            return $this->redirectToRoute('mood_index');
        }

        return $this->render('mood/new.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mood_show", methods={"GET"})
     */
    public function show(Mood $mood): Response
    {
        return $this->render('mood/show.html.twig', [
            'mood' => $mood,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mood_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mood $mood): Response
    {
        $form = $this->createForm(MoodType::class, $mood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mood_index');
        }

        return $this->render('mood/edit.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mood_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mood $mood): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mood->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mood);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mood_index');
    }
}
