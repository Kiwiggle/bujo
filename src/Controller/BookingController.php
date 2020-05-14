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

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/calendar", name="booking_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }

    /**
     * @Route("/new", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking_index');
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
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }


/**
* @Route("/dropUpdate/{id}", name="dropUpdate", requirements={"id"="\d+"}, options={"expose"=true})
*/
public function dropUpdate(BookingRepository $bookingRepo, Request $request, EntityManagerInterface $em)
{
    $request->isXmlHttpRequest();
    $data = $request->request->all();

    if (isset($data['start'], $data['end'], $data['id'])) {
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
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }
}
