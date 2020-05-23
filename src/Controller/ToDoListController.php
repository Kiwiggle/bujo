<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
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
        $fullToDoList = $toDoListRepository->findAll();

        for ($i = 0; $i < sizeof($fullToDoList); $i++) {
            ${"form" . $i} = $this->get('form.factory')->createNamed("form" . $i, ToDoListType::class, $fullToDoList[$i], array(
                'action'=> $this->generateUrl('todolist.index'),
                'method' => 'GET',
            ));
            ${"form" . $i}->handleRequest($request);

            $forms[] = ${"form" . $i}->createView();
        }

        for ($i = 0; $i < sizeof($fullToDoList); $i++) {
            if(${"form" . $i}->isSubmitted() && ${"form" . $i}->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }
        

        return $this->render('to_do_list/index.html.twig', [
            'to_do_lists' => $fullToDoList,
            'forms' => $forms
        ]);
    }

    /**
     * @Route("/new", name="todolist.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->redirectToRoute('to_do_list_index');
        }

        return $this->render('to_do_list/new.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todolist.show", methods={"GET"})
     */
    public function show(ToDoList $toDoList): Response
    {
        return $this->render('to_do_list/show.html.twig', [
            'to_do_list' => $toDoList,
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
     * @Route("/{id}", name="todolist.delete", methods={"DELETE"})
     */
    public function delete(Request $request, ToDoList $toDoList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toDoList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($toDoList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('to_do_list_index');
    }
}
