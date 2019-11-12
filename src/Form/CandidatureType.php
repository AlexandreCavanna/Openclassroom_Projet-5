<?php

namespace App\Form;

use App\Entity\Candidature;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CandidatureType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('cvFileName',FileType::class, $this->getConfiguration('Upload un Cv (PDF)','.   .   .', null, [

            'mapped' => false,

            'required' => false,

        'constraints' => [
            new File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                    'application/pdf',
                    'application/x-pdf',
                ],
                'mimeTypesMessage' => 'Please upload a valid PDF document',
            ])
        ],
    ]))
        ->add('coverLetter',TextareaType::class,$this->getConfiguration('Lettre de motivation','Tapez votre lettre de motivation', null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
