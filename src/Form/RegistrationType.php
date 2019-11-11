<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Votre prénom ...", null))
            ->add('lastname', TextType::class, $this->getConfiguration("Nom", "Votre nom ...", null))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre email ...", null))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un mot de passe ...", null))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe ...", null))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "Url de votre avatar ...", null))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Votre Introduction ...", null))
            ->add('description', TextType::class, $this->getConfiguration("Description", "Votre description ...", null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
