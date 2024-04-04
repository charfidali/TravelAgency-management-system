<?php

namespace App\Form;

use App\Entity\Vols;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination_aller', TextType::class, [
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ])
            ->add('destination_retour', TextType::class, [
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ])
            ->add('voyage', TextType::class, [
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ])
            ->add('date_depart', DateType::class, [

                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ])
            ->add('date_retour', DateType::class, [

                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control round-form'

                ]
            ])
            ->add('passagers', TextType::class, [
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ])
            ->add('cabine', TextType::class, [
                'attr' => [
                    'class' => 'form-control round-form'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vols::class,
        ]);
    }
}
