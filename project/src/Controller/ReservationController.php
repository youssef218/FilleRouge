<?php

namespace App\Controller;

use App\Entity\Evenemant;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
   

  #[Route('/api/reservation/{id}', name: 'app_add_reservation', methods: ['POST'])]
public function addReservation(EntityManagerInterface $entityManager, Evenemant $event ): Response
{
    $user = $this->getUser();

    if (!$user) {
        return new JsonResponse([
            'message' => 'You are not connected!',
        ]);
    }
    if (!$event) {
        return new JsonResponse([
            'message' => 'Event not found!',
        ]);
    }
    $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
        'client' => $user,
        'event' => $event,
    ]);

    if ($existingReservation) {
        $eventData = [
            'id' => $existingReservation->getEvent()->getId(),
            'title' => $existingReservation->getEvent()->getTitre(),
        ];
        return new JsonResponse([
            'message' => 'You already have a reservation for this event!',
            'event' => $eventData,
        ], Response::HTTP_CONFLICT);
    }

    $existingEvents = $entityManager->getRepository(Reservation::class)->createQueryBuilder('r')
    ->join('r.event', 'e')
    ->andWhere('r.client = :user')
    ->andWhere('e.debutAt < :finAt')
    ->andWhere('e.finAt > :debutAt')
    ->setParameter('user', $user)
    ->setParameter('debutAt', $event->getDebutAt())
    ->setParameter('finAt', $event->getFinAt())
    ->getQuery()
    ->getResult();

    if ($existingEvents) {
        return new JsonResponse([
            'message' => 'You cannot join this event because you have other overlapping events.',
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
       
    $reservation = new Reservation();
    $reservation->setClient($user);
    $reservation->setEvent($event);
    $reservation->setPrenstiel(true);

    $entityManager->persist($reservation);
    $entityManager->flush();

    return new JsonResponse([
        'message' => 'Reservation created successfully',
        'reservation_id' => $reservation->getId(),
    ]);
}
#[Route('/api/reservations', name: 'app_get_reservations', methods: ['GET'])]
public function getReservations(EntityManagerInterface $entityManager): JsonResponse
{
    $reservations = $entityManager->getRepository(Reservation::class)->findAll();

    $responseData = [];
    foreach ($reservations as $reservation) {
        $responseData[] = [
            'id' => $reservation->getId(),
            'client' => $reservation->getClient()->getFullName(),
            'event' => [
                'id' => $reservation->getEvent()->getId(),
                'titre' => $reservation->getEvent()->getTitre(),
                'debutAt' => $reservation->getEvent()->getDebutAt()->format('Y-m-d H:i:s'),
                'finAt' => $reservation->getEvent()->getFinAt()->format('Y-m-d H:i:s'),
            ],
            'createdAt' => $reservation->getCreatAt()->format('Y-m-d H:i:s'),
            'isPresent' => $reservation->isPrenstiel(),
        ];
    }

    return new JsonResponse($responseData);
}
   
#[Route('/api/delete/{id}', name: 'app_remove_reservation', methods: ['DELETE'])]
public function removeReservation(EntityManagerInterface $entityManager, int $id): JsonResponse
{
    $reservation = $entityManager->getRepository(Reservation::class)->find($id);

    if (!$reservation) {
        return new JsonResponse([
            'message' => 'Reservation not found!',
        ]);
    }

    $event = $reservation->getEvent();
    $debutAt = $event->getDebutAt();
    $currentDateTime = new \DateTimeImmutable();
    $timeDifference = $debutAt->diff($currentDateTime);

    if ($timeDifference->days < 2) {
        return new JsonResponse([
            'message' => "You can't remove the reservation because it starts soon.",
        ]);
    }

    $entityManager->remove($reservation);
    $entityManager->flush();

    return new JsonResponse([
        'message' => 'Reservation removed successfully',
    ]);
}

#[Route('/api/client/reservations', name: 'app_client_reservations', methods: ['GET'])]
public function displayClientReservations(ReservationRepository $repository, SerializerInterface $serializer): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'User not logged in'], 401);
    }

    $reservations = $repository->findBy(['client' => $user], ['event' => 'ASC']);

    if (empty($reservations)) {
        return new JsonResponse(['message' => 'You don\'t have any reservations'], 200);
    }

    // Extract event titles from reservations
    $reservationData = [];
    foreach ($reservations as $reservation) {
        $reservationData[] = [
            'id' => $reservation->getId(),
            'event' => $reservation->getEvent()->getTitre(),
            'createdAt' => $reservation->getCreatAt(),
            'prenstiel' => $reservation->isPrenstiel(),
        ];
    }

    $serializedReservations = $serializer->serialize($reservationData, 'json');

    return new JsonResponse($serializedReservations, 200, [], true);
}


}

