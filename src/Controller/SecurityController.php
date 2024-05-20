<?php

namespace App\Controller;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Swift_Mailer;
use Swift_Message;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/forgot", name="forgot")
     */
    //on a mis repository car il va faire appele a la methode fin one by qui dans le repository

    public function forgotPassword(Request $request, UserRepository $userRepository, Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator)
    {
        //recuperation du form
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);
        //tester si le form valide et submitted
        if($form->isSubmitted() && $form->isValid()) {
            //recuperation des données de form
            $donnees = $form->getData();

            //recherche si le mail est dans le base ou non 
            //email car on a juste le donnée email pour le recupére dans le form de reset
            $user = $userRepository->findOneBy(['email'=>$donnees]);
            //si n'existe pas
            if(!$user) {
                //addflash -> comme alert
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("app_login");

            }
            //token : c'est un jeton qui me donne l'accées a utiliser les fonctionalités des sites
            $token = $tokenGenerator->generateToken();

            try
            {
                //on lui donne un nv token 
                $user->setResetToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();

            }
             
            //si il y'a une exception
            catch(\Exception $exception)
            {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");
            }

            $url = $this->generateUrl('forgot',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);

            //BUNDLE MAILER

            $message = (new Swift_Message('Mot de password oublié'))
                //l'emil qui va envoyer les emails au utlisatuers
                ->setFrom('hjrmelek@gmail.com')
                //on va envoyer a lu'tilisateur qui a entrer son mail
                ->setTo($user->getEmail())
                //le message de succées
                ->setBody("<p> Bonjour</p> unde demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant :".$url,
                "text/html"); //text html car on amis de contenu html 

            //le contenu du mail qui va etre envoyé
            //send mail
            $mailer->send($message);
            $this->addFlash('message','E-mail  de réinitialisation du mp envoyé :');
            //return $this->redirectToRoute("app_login");

        }
        //dans le cas ou le n'est pas submitted; je reste dans le meme page
        return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);
    }






}
