<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Hotel;
use App\Entity\Commentaire;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\DateType;



class SearchAvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'created_at',
            DateTimeType::class,array('input' => 'datetime_immutable'),[
            'html5'  => false,
            'format' => 'dd-MM-yyyy'],
             
         ['attr'=>['class' => 'form-control js-datepicker','placeholder'=>"Date de naissance"]]
        )
           
             ->add('Rechercher', SubmitType::class, ['attr'=>['class' => 'btn btn-log btn-block btn-thm2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
