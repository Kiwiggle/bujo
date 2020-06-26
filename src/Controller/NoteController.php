<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/bujo/notes")
 */
class NoteController extends AbstractController
{

    public function __construct(Security $security) 
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="note.index", methods={"GET"}, options={"expose"=true})
     */
    public function index(NoteRepository $noteRepository): Response
    {
        $user = $this->security->getUser();
        $user = $user->getId();
        return $this->render('note/index.html.twig', [
            'notes' => $noteRepository->findAllByUser($user),
        ]);
    }

    /**
     * @Route("/new", name="note.new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        if ($request->isXmlHttpRequest()) {

            $data = $request->request->all();
            $note->setTitle($data['title']);
            $note->setContent($data['outputData']);

            $user = $this->security->getUser();
            $note->setUser($user);


            $date = new DateTime();
            $date->format('Y-m-d');
            $note->setDate($date);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();

            return new Response();
        }


        return $this->render('note/new.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
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
            $note->setContent($data);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

        }
        
        return $this->render('note/edit.html.twig', [
            'note' => $note
        ]);
    }

    /**
     * @Route("/delete/{id}", name="note.delete", methods={"DELETE"}, requirements={"id"="\d+"}, options={"expose"=true})
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
    public function getNote($id, Note $note, NoteRepository $noteRepo): Response
    {
        $note = $noteRepo->find($id);
        return new JsonResponse($note->getContent());
    }

}