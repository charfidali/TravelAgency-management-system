<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VoitureType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('marque')
            ->add('model')
            ->add('nbsieges')
            ->add('prix')
            ->add('type' , ChoiceType::class, [
                'choices'  => [
                    'Suv' => "suv",
                    'lux' => "lux",
                    'eco' => "eco",
                    'touristique' => "touristique"
                ],
                    ])

            ->add('image', FileType::class,[
        'mapped'=> false,
        'label'=>' please upload a image'

    ])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
