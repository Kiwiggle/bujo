<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="note.index", methods={"GET"})
     */
    public function index(NoteRepository $noteRepository): Response
    {
        return $this->render('note/index.html.twig', [
            'notes' => $noteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="note.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('note.index');
        }

        return $this->render('note/new.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note.show", methods={"GET"})
     */
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="note.edit", methods={"GET","POST"}, requirements={"id"="\d+"}, options={"expose"=true})
     */
    public function edit($id, Request $request, Note $note, NoteRepository $noteRepo): Response
    {
        $note = $noteRepo->find($id);

        $data = $request->request->get('outputData');

        if (!empty($data)){
            dump($data);
            $note->setContent($data);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

        }
        
        return $this->render('note/edit.html.twig', [
            'note' => $note
        ]);
    }

    /**
     * @Route("/{id}", name="note.delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Note $note): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('note.index');
    }

    /**
     * @Route("/get/{id}", name="note.get", methods={"GET"}, requirements={"id"="\d+"}, options={"expose"=true})
     */
    public function getNote($id, Request $request, Note $note, NoteRepository $noteRepo): Response
    {
        $note = $noteRepo->find($id);
        return new JsonResponse($note->getContent());
    }

}