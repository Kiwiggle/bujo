<?php

namespace App\Controller;

use App\Entity\Mood;
use App\Entity\MoodSearch;
use App\Form\MoodType;
use App\Form\MoodSearchType;
use App\Repository\MoodRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/mood/archives")
 */
class MoodArchivesController extends AbstractController
{
    
    /**
     * @Route("/", name="mood.archives")
     */
    public function moodArchives(Request $request, MoodRepository $moodRepo) : Response
    {

        $search = new MoodSearch();
        $form = $this->createForm(MoodSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $date = $form->getViewData();
            $date = $date->getdate();
            $date = $date->format('Y-m-d');
            $mood = $moodRepo->findByDate($date);
            dump($mood);

            return $this->render('mood/archives.html.twig', [
                "form" => $form->createView(),
                "mood" => $mood
            ]);
        }

        return $this->render('mood/archives.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{month}", name="mood.archives.month", requirements={"month":"\d+"}, options={"expose"=true})
     */
    public function archivesMonth($month, Request $request, MoodRepository $moodRepo) {
        $monthMood = $moodRepo->findByMonth($month);
        dump($monthMood);

        return new JsonResponse($monthMood);
    }

}

