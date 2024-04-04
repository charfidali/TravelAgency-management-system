<?php

namespace App\Form;

use App\Entity\Chambres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Hotels;

class Chambres1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeChambre')
            ->add('nbrLit')
            ->add('description')
            ->add('prix')
            ->add('image',FileType::class,[
                'mapped'=> false,
                'label'=>' please upload a image'
            ])
            ->add('hotels', EntityType::class, [
                'class' => Hotels::class,
                'choice_label' => 'nom',
                'multiple'=> false,
                'expanded' => false
            ])
            ->add('ajouter', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambres::class,
        ]);
    }
}

