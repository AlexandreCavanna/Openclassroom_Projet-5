<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom *", "Votre prénom ...", null, null))
            ->add('lastname', TextType::class, $this->getConfiguration("Nom *", "Votre nom ...", null, null))
            ->add('email', EmailType::class, $this->getConfiguration("Email *", "Votre email ...", null, null))
            ->add('emailConfirm', EmailType::class, $this->getConfiguration("Confirmation de votre email *", "Veuillez confirmer votre email ...", null, null))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe *", "Choisissez un mot de passe ...", null, null))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe *", "Veuillez confirmer votre mot de passe ...", null, null))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil ( Laissez par défaut si vous ne voulez pas changer d'avatar )", "Url de votre avatar ...", null, null, ['attr' => ['value' => 'https://image.flaticon.com/icons/svg/1177/1177568.svg']]))
            ->add('introduction', CKEditorType::class, array(
                'label' => 'Introduction *',
                'config' => array(
                    'placeholder' => 'Si vous êtes un étudiant présentez le poste que vous recherchez / Si vous êtes un employeur présentez votre fonction au sein de votre société...',
                    'config_name' => 'my_config',
                ), 
            ))
            ->add('description', CKEditorType::class, array(
                'label' => 'Description *',
                'config' => array(
                    'placeholder' => 'Si vous êtes un étudiant décrivez vous pour l\'employeur / Si vous êtes un employeur décrivez quel genre d\'apprentis vous recherchez...',
                    'config_name' => 'my_config'
                )
            ))
            ->add('registerStudent', SubmitType::class, $this->getConfiguration("Inscription étudiant", null, 'btn-primary btn-lg rounded', null))
            ->add('registerEmployer', SubmitType::class, $this->getConfiguration("Inscription employeur", null, 'btn-primary btn-lg rounded', null));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
