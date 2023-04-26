<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTservice;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/signin', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, 
    EntityManagerInterface $entityManager, SendMailService $mail, JWTservice $jwt ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            //jwt create
            $header = [
                "type" => 'jwt',
                "alg" => 'HS256'
            ];

            $payload = [
                "user_id" => $user->getId(),

            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            
            // do anything else you need here, like send an email

            $mail->Send(
                'no-reply@monsite.fr',
                $user->getEmail(),
                'Activation de votre compte sur le site xxxxxxxxx.fr',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token,
     JWTservice $jwt,
      UserRepository $userRepository,
       EntityManagerInterface $em): Response
    {
        if($jwt->isValid($token) && !$jwt->isExpired($token) 
        && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            $payload = $jwt->getPayload($token);

            //recupe user
            $user = $userRepository->find($payload['user_id']);

            //verif existe et compte non actif, pour activer
            if($user && !$user->getIsVerified()){
                $user->setVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'compte activé');
                return $this->redirectToRoute('profile_index');

            }
        }

        $this->addFlash('danger', 'token invalide ou expiré');

        return $this->redirectToRoute('app_main');
    }

    #[Route('/resend-verif', name: 'resend_verif')]
    public function resendVerif(JWTservice $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('danger', 'Connexion necessaire');
            return $this->redirectToRoute('app_login');
        }

        if($user->getIsVerified()){
            $this->addFlash('warning', 'déjà verifié');
            return $this->redirectToRoute('profile_index');
        }

        //jwt create
        $header = [
            "type" => 'jwt',
            "alg" => 'HS256'
        ];

        $payload = [
            "user_id" => $user->getId(),

        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
        
        // do anything else you need here, like send an email

        $mail->Send(
            'no-reply@monsite.fr',
            $user->getEmail(),
            'Activation de votre compte sur le site xxxxxxxxx.fr',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );

        $this->addFlash('success', 'email send');
        return $this->redirectToRoute('profile_index');
    }

}
