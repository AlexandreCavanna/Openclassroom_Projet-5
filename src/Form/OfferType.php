<?php

namespace App\Form;

use App\Entity\Offer;
use App\Form\ApplicationType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OfferType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre de l'offre * :", "Tapez un titre pour votre offre", null, null, ['attr' => ['value' => '']])
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web :", "Tapez l'adresse web (automatique)", null, null, ['attr' => ['value' => ''], 'required' => false])
            )
            ->add('description', CKEditorType::class, array(
                'label' => 'Description de l\'offre * :',
                'config' => array(
                    'placeholder' => 'Décrivez votre offre...',
                    'basicEntities' => false,
                    'autoParagraph' => false,
                    'ignoreEmptyParagraph' => false
                ), 
            ))
            ->add('addOffer', SubmitType::class, $this->getConfiguration("Publier une offre", null, 'btn-success btn-lg rounded', null));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
