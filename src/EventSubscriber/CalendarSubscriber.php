<?php

namespace App\EventSubscriber;

use App\Repository\BookingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $bookingRepository;
    private $router;

    public function __construct(BookingRepository $bookingRepository, UrlGeneratorInterface $router, Security $security) 
    {
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        $user = $this->security->getUser();
        $user = $user->getId();

        // Modify the query to fit to your entity and needs
        // Cherche tous les événements liés à un user
        $bookings = $this->bookingRepository
            ->createQueryBuilder('booking')
            ->andWhere('booking.beginAt BETWEEN :start and :end OR booking.endAt BETWEEN :start and :end')
            ->andWhere('booking.user = :user')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;

        foreach ($bookings as $booking) {
            // Création des événements avec les données récupérées pour remplir le calendar
            $bookingEvent = new Event(
                $booking->getTitle(),
                $booking->getBeginAt(),
                $booking->getEndAt() 
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $bookingEvent->setOptions([
                'backgroundColor' => '#77d07a',
                'borderColor' => '#77d07a'
            ]);

            $bookingEvent->addOption(
                'url',
                $this->router->generate('booking_show', [
                    'id' => $booking->getId(),
                ])
            );

            // Ajout de l'événement au calendrier
            $calendar->addEvent($bookingEvent);
        }
    }
}