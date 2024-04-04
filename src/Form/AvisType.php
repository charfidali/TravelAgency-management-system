<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', ChoiceType::class, [
                'choices'  => [
                    'étoile' => 1,
                    '2 étoiles' => 2,
                    '3 étoiles' => 3,
                    '4 étoiles'=> 4 ,
                    '5 étoiles'=> 5 ,
                ] ])      
            
            ->add('Noter', SubmitType::class, ['attr'=>['class' => 'btn btn-log btn-block btn-thm2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
