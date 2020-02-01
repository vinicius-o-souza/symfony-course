<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Event\UserSubscriber;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder, 
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        TokenGenerator $tokenGenerator,
        \Swift_Mailer $mailer
    )
    {
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword(
                $user, 
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $userRegisterEvent = new UserRegisterEvent($user);
            // $subscriber = new UserSubscriber($mailer);
            // $eventDispatcher->addSubscriber($subscriber);
            $eventDispatcher->dispatch($userRegisterEvent);
            
            return $this->redirectToRoute('micro_post_index');
        }
        
        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}