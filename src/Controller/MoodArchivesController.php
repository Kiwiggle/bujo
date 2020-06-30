<?php

namespace App\Controller;

use App\Entity\MoodSearch;
use App\Form\MoodSearchType;
use App\Repository\MoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/bujo/mood/archives")
 */
class MoodArchivesController extends AbstractController
{

    public function __construct(Security $security) 
    {
        $this->security = $security;
    }
    
    /**
     * @Route("/", name="mood.archives")
     * render la page Archives, avec une partie formulaire recherche d'un jour particulier + gestion du formulaire en cas d'envoi
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

            $user = $this->security->getUser();
            $user = $user->getId();
            $mood = $moodRepo->findByDate($date, $user);

            if (empty($mood)) {
                return $this->render('mood/archives.html.twig', [
                    "form" => $form->createView(),
                    "error" => "Aucun résultat trouvé pour cette recherche."
                ]);
            } else {
                return $this->render('mood/archives.html.twig', [
                    "form" => $form->createView(),
                    "mood" => $mood
                ]);
            }
        }

        return $this->render('mood/archives.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Suite à requête AJAX, envoie les données correspondant à un mois entier de Moods pour construire un graphique en Javascript en front
     * @Route("/{month}", name="mood.archives.month", requirements={"month":"\d+"}, options={"expose"=true})
     */
    public function archivesMonth($month, Request $request, MoodRepository $moodRepo) {
            $user = $this->security->getUser();
            $user = $user->getId();
            $monthMood = $moodRepo->findByMonth($month, $user);

            return new JsonResponse($monthMood);
        
    }

}

