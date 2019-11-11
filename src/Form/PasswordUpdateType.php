<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Ancien mot de passe","Donnez votre ancien mot de passe actuel", null))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("Nouveau mot de passe","Donnez votre nouveau mot de passe", null))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirmer votre mot de passe","Confirmer votre nouveau mot de passe", null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
