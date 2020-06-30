<?php

namespace App\Controller;

use App\Entity\Mood;
use App\Form\MoodType;
use App\Repository\MoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/bujo/mood")
 */
class MoodController extends AbstractController
{

    public function __construct(Security $security) 
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="mood.index", methods={"GET"})
     */
    public function index(MoodRepository $moodRepository): Response
    {
        return $this->render('mood/index.html.twig');
    }

     /**
     * @Route("/today", name="mood.today", methods={"GET", "POST"})
     * Gestion du Mood d'aujourd'hui
     */
    public function moodToday(Request $request, MoodRepository $moodRepo) 
    {
        //Recherche d'abord le mood du jour, s'il a déjà été rempli aujourd'hui.
        $today = date('Y-m-d');
        $user = $this->security->getUser();
        $user = $user->getId();
        $todayMood = $moodRepo->findByDate($today, $user);

        //si le mood a bien été rempli
        if (empty($todayMood)) {
            //Nouveau formulaire pour le créer
            $newForm = $this->new('today', $request);
            return $newForm;
        } else {
            //Ou renvoie le mood d'aujourd'hui
            return $this->render('mood/today.html.twig', [
                'today' => $todayMood
            ]);
        }
    }

    /**
     * @Route("/{id}/edit", name="mood.edit", methods={"GET","POST"}, requirements={"id"="\d+"}, options={"expose"=true})
     * Modifie le mood d'aujourd'hui
     */
    public function edit($id, Request $request, Mood $mood): Response
    {
        $form = $this->createForm(MoodType::class, $mood, array(
            'action'=> $this->generateUrl('mood.edit', ['id' => $id]),
            'method' => 'GET'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mood.index');
        }

        return $this->render('mood/_form.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
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
     * @Route("/new", name="mood.new", methods={"GET","POST"})
     */
    private function new(String $path, Request $request): Response
    {
        $mood = new Mood();
        $form = $this->createForm(MoodType::class, $mood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $this->security->getUser();
            $mood->setUser($user);

            $entityManager->persist($mood);
            $entityManager->flush();

            return $this->redirectToRoute('mood.index');
        }

        return $this->render('mood/'. $path .'.html.twig', [
            'mood' => $mood,
            'form' => $form->createView(),
        ]);
    }
}

