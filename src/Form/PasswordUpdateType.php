<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Ancien mot de passe", "Entrez votre ancien mot de passe actuel", null, null))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("Nouveau mot de passe", "Entrez votre nouveau mot de passe", null, null))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirmer votre mot de passe", "Confirmer votre nouveau mot de passe", null, null))
            ->add('updatePassword', SubmitType::class, $this->getConfiguration("Mettre à jour mot de passe", null, 'btn-success btn-lg rounded', null));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
