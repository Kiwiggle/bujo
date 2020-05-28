<?php

namespace App\Controller;

use App\Entity\Mood;
use App\Form\MoodType;
use App\Repository\MoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mood/archives")
 */
class MoodArchivesController extends AbstractController
{
    
    /**
     * @Route("/", name="mood.archives")
     */
    public function moodArchives(Request $request, MoodRepository $moodRepo) {
        return $this->render('mood/archives.html.twig');
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

