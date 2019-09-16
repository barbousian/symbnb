<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends AbstractType
{
    /**
     * permet d'avoir la configuration de base d'un champs
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options=[]) {
        return array_merge([
            'label' => $label,
                'attr' => [
                    'placeholder' => $placeholder
                ]
            ], $options);

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration(
                'titre', 'donnez un super titre à votre annonce'))
            ->add('slug', TextType::class, $this->getConfiguration(
                'Chaine URL', 'Adresse WEB (automatique)', [
                    'required' => false
                ]))
            ->add('coverImage', UrlType::class, $this->getConfiguration(
                "Url de l'Image", "donnez l'Url de l'image"))
            ->add('introduction', TextType::class, $this->getConfiguration(
                'introduction', 'donnez une description globale de votre location'))
            ->add('content', TextareaType::class, $this->getConfiguration(
                'description', 'donnez une description détaillée de votre location'))
            ->add('price', MoneyType::class,$this->getConfiguration (
                'prix par nuit', 'Indiquez le prix que vous voulez'))
            ->add('rooms', IntegerType::class, $this->getConfiguration(
                "Nb de chambres", "donnez le nombre de chambre de votre location"))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
