<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todolist")
 */
class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="todolist.index", methods={"GET"})
     */
    public function index(Request $request, ToDoListRepository $toDoListRepository): Response
    {

        //Je cherche la liste du jour
        $today = date('Y-m-d');
        $todayList = $toDoListRepository->findByDate($today);

        return $this->loadForms($todayList, $request, $today, 'index');
        
    }

    /**
     * @Route("/previous", name="todolist.previous", options={"expose"=true}, methods={"GET", "POST"})
     */
    public function previousList(Request $request, ToDoListRepository $toDoListRepository) {
        $date = $request->request->get('date');
        $list = $toDoListRepository->findByDate($date);

        $dateFormatted = new DateTime($date);
        $dateFormatted->format('d m Y');

        return $this->loadForms($list, $request, $dateFormatted, '_form');
    }

    /**
     * @Route("/new", name="todolist.new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $toDoList, array(
            'action'=> $this->generateUrl('todolist.new'),
            'method' => 'POST',
    ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $today = new \DateTime();
            $today->format('d-m-Y');
            $toDoList->setDate($today);
            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->redirectToRoute('todolist.index');
        }

        return $this->render('to_do_list/new.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="todolist.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ToDoList $toDoList): Response
    {
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todolist.index');
        }

        return $this->render('to_do_list/edit.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="todolist.delete")
     */
    public function delete(Request $request, ToDoList $toDoList): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($toDoList);
            $entityManager->flush();

        return $this->redirectToRoute('todolist.index');
    }

    private function loadForms(array $list, Request $request, $date, string $templateName) {

        if (! $list == null) {
            //Je créée un formulaire checkbox/input/save pour chaque entrée de la bdd
            for ($i = 0; $i < sizeof($list); $i++) {
                ${"form" . $i} = $this->get('form.factory')->createNamed(
                    "form" . $i, 
                    ToDoListType::class, 
                    $list[$i], 
                    array(
                        'action'=> $this->generateUrl('todolist.index'),
                        'method' => 'GET',
                        'csrf_protection' => false
                ));
                ${"form" . $i}->handleRequest($request);

                $ids[] = $list[$i]->getId();
                $forms[] = ${"form" . $i}->createView();
            }

            //Je créée la gestion et modification de chaque formulaire
            for ($i = 0; $i < sizeof($list); $i++) {
                if(${"form" . $i}->isSubmitted() && ${"form" . $i}->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                }
            }

            //Je formate la date pour le rendu HTML
            if (is_object($date)) {
                dump($date);
                $formattedDate = new DateTime($date->date);
                $date = $formattedDate->format('d m Y');
            } else {
                $formattedDate = new DateTime($date);
                $date = $formattedDate->format('d m Y');
            }
            

            return $this->render('to_do_list/' . $templateName . '.html.twig', [
                'ids' => $ids,
                'to_do_lists' => $list,
                'forms' => $forms,
                'date' => $date
            ]);

        } else {

            return $this->render('to_do_list/'. $templateName .'.html.twig', [
                'error' => 'Créez une nouvelle liste :)'
            ]);

        }
    }

    private function formatDateTime($date) {
        $formattedDate = new DateTime($date);
        $date = $formattedDate->format('d m Y');
    }
}
