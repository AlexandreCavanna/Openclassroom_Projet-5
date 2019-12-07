<?php

namespace App\Form;

use App\Entity\Candidature;
use App\Form\ApplicationType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CandidatureType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cvFileName', FileType::class, $this->getConfiguration(null, null, null, null, [

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Votre fichier n\'est pas un pdf.',
                    ])
                ],
            ]))
            ->add('coverLetter', CKEditorType::class, array(
                'label' => 'Lettre de motivation :',
                'config' => array(
                    'placeholder' => 'Tapez votre lettre de motivation',
                    'basicEntities' => false,
                    'autoParagraph' => false,
                    'ignoreEmptyParagraph' => false
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
