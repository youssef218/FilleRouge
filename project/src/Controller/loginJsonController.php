<?php
 
namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class loginJsonController extends AbstractController
{
    private $Passwordhasher;
    public function __construct(UserPasswordHasherInterface $Passwordhasher)
    {
        $this->Passwordhasher = $Passwordhasher;
    }

    #[Route('/api/register', name: 'api_user_create', methods: ['POST'])]
    public function regesterForme(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the form data from the request body
        $formData = json_decode($request->getContent(), true);
        $user = $this->getUser();
        if($user){
    return new JsonResponse([
        'message' => 'you can`t register now because your connected!',
        'user' => $user->getUserIdentifier(),
    ]);
}
        // Handle the form data and perform any necessary actions
        // For example, you can persist the data to the database using Doctrine
        
        // Assuming you have an Entity class named `User` to represent the data
        $plainPassword = $formData['password'];
        $userRegester = new User();
        $hashedPassword = $this->Passwordhasher->hashPassword($userRegester, $plainPassword);
        $userRegester->setEmail($formData['email']);
        $userRegester->setPassword($hashedPassword);
        $userRegester->setTeleportable($formData['tele']);
        $userRegester->setAdress($formData['ville']);
        $userRegester->setCin($formData['cin']);
        $userRegester->setFullName($formData['fullName']);

        if(isset($formData['role'])){
            $userRegester->setNfiscale($formData['nfiscal']);
            $userRegester->setRoles([$formData['role']]);
        }
        

        // Persist the entity
        // dd($userRegester);
        $entityManager->persist($userRegester);
        $entityManager->flush();

        // Return a response if needed
        return $this->json(['message' => ' successfully added to our.']);

    }

#[Route('/api/profile', name: 'api_profile', methods:['GET'])]
public function profile(SerializerInterface $serializer): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse([
            'message' => 'You are not connected',
        ], Response::HTTP_UNAUTHORIZED);
    }
    
    $serializedUser = $serializer->serialize($user, 'json', ['groups' => ['user']]);
    return new JsonResponse($serializedUser, Response::HTTP_OK, [], true);
}

    #[Route('/api/profile/{id}', name: 'api_profile_update', methods: ['PUT'])]
    public function updateProfile(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id): Response
    {

    
        // Find the user to be updated
        $userToUpdate = $userRepository->find($id);
        if (!$userToUpdate) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        // Get the form data from the request body
        $formData = json_decode($request->getContent(), true);
    
        // Update the user's role if the condition is met
        if (isset($formData['change_role']) && $formData['change_role'] === true) {
            $userToUpdate->setRoles(['ROLE_ORGANISATEURE']);
        }
    
        // Persist the changes
        $entityManager->persist($userToUpdate);
        $entityManager->flush();
    
        // Return a response if needed
        return new JsonResponse(['message' => 'User profile updated successfully'], Response::HTTP_OK);
    }

    #[Route('/api/organisateurs', name: 'api_users', methods: ['GET'])]
    public function getUsersByRole(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $userRepository->findall();
        // If no users with the specified role are found, return an appropriate response
        if (empty($users)) {
            return new JsonResponse(['message' => 'No users with the specified role found'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        // Serialize the users to JSON
        $serializedUsers = $serializer->serialize($users, 'json', ['groups' => ['user']]);
        
        // Return the serialized users as a JSON response
        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, [], true);
    }
    
}
