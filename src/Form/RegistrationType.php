<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre courriel..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo", "Url de Votre bouille en photo..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "choissez votre mot de passe..."))
            ->add('hashConfirm', PasswordType::class, $this->getConfiguration("--", "Répétez votre mot de passe..."))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "une présentation en une ligne..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Présentez vous de façon détaillée..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
