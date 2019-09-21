<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et de gérer le formulaire de connexion
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }
    /**
     * permet de se déconnecter
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {
        // .. rien
    }

    /**
     * permet d'inscrire un nouvel utilisateur
     * @Route("/register", name="account_register")
     * 
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hash=$encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success', "Bienvenue dans notre site ! Vous pouvez vous connecter"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
    /**
     * permet d'éditer le profil de l'utilisateur
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success', "Modification du profil effectuée"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
 
    }

    /**
     * permet de modifier le mot de passe de l'utilisateur
     * @Route("/account/password-update ", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $passwordUpdate = new PasswordUpdate();
        /*
            on vérifie le password
        */
        $user=$this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            
            if(password_verify($passwordUpdate->getOldPassword(),$user->getHash())) {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success', "Modification du password effectuée"
                );
                return $this->redirectToRoute('homepage');
            } else {
                $form->get('oldPassword')->addError(new FormError("Saisie de l'ancien mot de passe erronée"));
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher le profil de l'utilisateur connecté
     * @route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
