<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/bujo/calendar")
 */
class BookingController extends AbstractController
{

    public function __construct(Security $security) 
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="booking.calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }

    /**
     * @Route("/new", name="booking_new", methods={"GET","POST"}, options={"expose"=true})
     * Requête ajax -> envoie un formulaire pour créer un nouvel événement, et gère ensuite l'envoi du form
     */
    public function new(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking, array(
            'action'=> $this->generateUrl('booking_new'),
            'method' => 'GET'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $this->security->getUser();
            $booking->setUser($user);

            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking.calendar');
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"}, options={"expose"=true})
     * Requête Ajax - envoie le form pour éditer un événement
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking, array(
            'action'=> $this->generateUrl('booking_edit', ['id' => $booking->getId()]),
            'method' => 'GET'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking.calendar');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }


    /**
    * @Route("/dropUpdate/{id}", name="dropUpdate", requirements={"id"="\d+"}, options={"expose"=true})
    * Ajax - gestion du drag'n'drop d'un événement sur le calendrier (changement de jour)
    */
    public function dropUpdate(BookingRepository $bookingRepo, Request $request, EntityManagerInterface $em)
    {
        $data = $request->request->all();

        if ($request->isXmlHttpRequest() && isset($data['start'], $data['end'], $data['id'])) {
            //Je mets les dates au bon format
            $startOk = new \DateTime($data['start']);
            $startOk->format('Y-m-d H:i:s');
            $endOk = new \DateTime($data['end']);
            $endOk->format('Y-m-d H:i:s');

            //J'active l'Entity Manager
            $em = $this->getDoctrine()->getManager();

            //Je récupère l'event que j'ai besoin de modifier
            $booking = $bookingRepo
                ->find($data['id']);
                
            //Je change les dates
            $booking
                ->setBeginAt($startOk)
                ->setEndAt($endOk);

            //J'envoie en BDD
            $em->flush();

            //Réponse status 200 traitée par la requête ajax
            $response = new Response(Response::HTTP_OK);
            $response->setContent(json_encode([ 'success' => "La mise à jour a bien été effectuée"]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            $response = new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent(json_encode([ 'error' => "Le serveur n'a pas pu récupérer les données"]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
    }

    /**
    * @Route("/loadEvent/{id}", name="booking.loadEvent", requirements={"id"="\d+"}, options={"expose"=true})
    * Ajax - envoie les infos d'un événement après un clic sur le calendrier
    */
    public function loadOneEvent($id, BookingRepository $bookingRepo, Request $request) 
    {

        $booking = $bookingRepo->find($id);

        return new Response (
            '<div class="event-card"> 
                <div class="event-card-design">
                    <h3>' . $booking->getTitle(). '</h3> 
                    <p> Description : ' . $booking->getDescription() . ' </p> 
                    <p> Début le : ' . $booking->getBeginAt()->format('d/m/Y à H:i') . ' </p> 
                    <p> Fin le : ' . $booking->getEndAt()->format('d/m/Y à H:i') . ' </p> 
                    <p> Adresse : ' . $booking->getPlace() . ' </p>
                    <a id="edit-event" class="btn fc-button-primary">Modifier l\'événement</a>
                </div>
            </div>'
        );

    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking.calendar');
    }
}
