<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]

class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {

        return new JsonResponse(['message' => 'Logged in successfully'], 200);

    }
    #[Route('/register', name: 'app_register',methods:'POST')]
    public function register(UserPasswordHasherInterface  $passwordHacher,
                         EntityManagerInterface $entityManager,
                         Request $request){
        $data = json_decode($request->getContent(),true);
        $user =new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $hashedPassword = $passwordHacher->hashPassword($user,$data['password']);
        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse(['message','User successfully added'],201);



   }

}
