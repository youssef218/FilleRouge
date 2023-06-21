<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Evenemant;
use App\Repository\EvenemantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class AddEvenementController extends AbstractController
{
    #[Route('/api/add/evenement', name: 'app_add_evenement' , methods:['POST'])]
    public function addEvent(Request $request, EntityManagerInterface $entityManager): Response
        {
         $user = $this->getUser();
     if (!$user) {
         return new JsonResponse([
               'message' => 'you are not connected!',
        ]);
       }
            // Get the form data from the request body 
            $formData = json_decode($request->getContent(), true);
            $event = new Evenemant();
            $event->setTitre($formData['titre']);
            $event->setDescription($formData['discription']);
            $debutAtString = $formData['debutAt'];
            $debutAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $debutAtString);
            if ($debutAt !== false) {
                $event->setDebutAt($debutAt);
            } else {
                return new JsonResponse(['error' => 'format invalide'], 402);
            }
            if(!isset($formData['isPublic'])){
                $event->setIsPublic(false);
            }else{
                $event->setIsPublic($formData['isPublic']);
            }
            $finAtString = $formData['finAt'];
            $finAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $finAtString);
            if ($finAt !== false) {
                $event->setFinAt($finAt);
            } else {
                return new JsonResponse(['error' => 'format invalide'], 402);
            }
            
            $event->setAdmin($user);
    
            // Persist the entity
            $entityManager->persist($event);
            $entityManager->flush();
    
            // Return a response if neeisPublicded
            return $this->json(['message' => ' successfully added Evenement.']);
    
        }
        #[Route('/api/update/evenement/{id}', name: 'app_update_evenement', methods: ['PUT'])]
        public function updateEvent(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $event = $entityManager->getRepository(Evenemant::class)->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Evenement not found'], 404);
        }

        // Get the form data from the request body
        $formData = json_decode($request->getContent(), true);

        // Update the specific fields if provided in the form data
        if (isset($formData['titre'])) {
            $event->setTitre($formData['titre']);
        }

        if (isset($formData['description'])) {
            $event->setDescription($formData['description']);
        }

        if (isset($formData['debutAt'])) {
            $debutAtString = $formData['debutAt'];
            $debutAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $debutAtString);
            if ($debutAt !== false) {
                $event->setDebutAt($debutAt);
            } else {
                return new JsonResponse(['error' => 'Invalid date format'], 402);
            }
        }

        if (isset($formData['isPublic'])) {
            $event->setIsPublic($formData['isPublic']);
        }

        if (isset($formData['finAt'])) {
            $finAtString = $formData['finAt'];
            $finAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $finAtString);
            if ($finAt !== false) {
                $event->setFinAt($finAt);
            } else {
                return new JsonResponse(['error' => 'Invalid date format'], 402);
            }
        }

        // Update the entity
        $entityManager->flush();

        // Return a response if needed
        return $this->json(['message' => 'Successfully updated Evenement.']);
    }

    #[Route('/api/remove/evenement/{id}', name: 'app_remove_evenement', methods: ['DELETE'])]
public function removeEvent(EntityManagerInterface $entityManager, $id): Response
{
    $event = $entityManager->getRepository(Evenemant::class)->find($id);

    if (!$event) {
        return new JsonResponse(['error' => 'Evenement not found'], 404);
    }

    // Remove the event
    $entityManager->remove($event);
    $entityManager->flush();

    // Return a response if needed
    return $this->json(['message' => 'Successfully removed Evenement.']);
}



#[Route('/api/client/events', name: 'app_client_events', methods: ['GET'])]
public function displayClientEvents(EvenemantRepository $repository, SerializerInterface $serializer): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'User not logged in'], 401);
    }

    $events = $repository->findBy(['admin' => $user]);

    if (empty($events)) {
        return new JsonResponse(['message' => 'You don\'t have any events'], 200);
    }

    $serializedEvents = $serializer->serialize($events, 'json', ['groups' => ['event']]);

    return new JsonResponse($serializedEvents, 200, [], true);
}

#[Route('/api/events/{id}', name: 'api_id_events', methods: ['GET'])]
public function displayClientEvent(int $id, EvenemantRepository $repository, SerializerInterface $serializer): JsonResponse
{
    // $user = $this->getUser();
    // if (!$user) {
    //     return new JsonResponse(['error' => 'User not logged in'], 401);
    // }

    $event = $repository->findOneBy(['id' => $id]);

    if (!$event) {
        return new JsonResponse(['message' => 'Event not found'], 404);
    }

    $serializedEvent = $serializer->serialize($event, 'json', ['groups' => ['event']]);

    return new JsonResponse($serializedEvent, 200, [], true);
}


#[Route('/api/events', name: 'api_public_events', methods: ['GET'])]
public function displaypublicEvent(EvenemantRepository $repository, SerializerInterface $serializer): JsonResponse
{

    $event = $repository->findAll();

    if (!$event) {
        return new JsonResponse(['message' => 'Event not fund'], 404);
    }

    $serializedEvent = $serializer->serialize($event, 'json', ['groups' => ['event']]);

    return new JsonResponse($serializedEvent, 200, [], true);
}
    }

