<?php

namespace App\Controller;

use App\Entity\Mood;
use App\Form\MoodType;
use App\Repository\MoodRepository;
use DateTime;
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
     * @Route("/", name="mood.index", methods={"GET"})
     */
    public function index(MoodRepository $moodRepository): Response
    {
        return $this->render('mood/index.html.twig', [
            'moods' => $moodRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mood.new", methods={"GET","POST"})
     */
    public function new(String $path, Request $request): Response
    {
        $mood = new Mood();
        $form = $this->createForm(MoodType::class, $mood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mood);
            $entityManager->flush();

            return $this->redirectToRoute('mood.index');
        }

        return $this->render('mood/'. $path .'.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mood.show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Mood $mood): Response
    {
        return $this->render('mood/show.html.twig', [
            'mood' => $mood,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mood.edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Mood $mood): Response
    {
        $form = $this->createForm(MoodType::class, $mood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mood.index');
        }

        return $this->render('mood/edit.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/today", name="mood.today", methods={"GET", "POST"})
     */
    public function moodToday(Request $request, MoodRepository $moodRepo) {
        $today = date('Y-m-d');
        $todayMood = $moodRepo->findByDate($today);
        dump($todayMood);

        if (empty($todayMood)) {
            $newForm = $this->new('today', $request);
            return $newForm;
        } else {
            return $this->render('mood/today.html.twig', [
                'today' => $todayMood
            ]);
        }
        

    }

    /**
     * @Route("/{id}", name="mood.delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Mood $mood): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mood->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mood);
            $entityManager->flush();
        } 

        return $this->redirectToRoute('mood.index');
    }

      /**
     * @Route("/archives", name="mood.archives")
     */
    public function moodArchives(Request $request, MoodRepository $moodRepo) {
        return $this->render('mood/archives.html.twig');
    }
}

