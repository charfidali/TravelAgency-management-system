<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Vich\UploaderBundle\Form\Type\VichFileType;
class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
     ->add('img_name', FileType::class,[
            'mapped' => false
        ])
        ->add('usernamee',TextType::class, ['attr'=>['class' => 'form-control','placeholder'=>"Pseudo"]])
        ->add('email',TextType::class, ['attr'=>['class' => 'form-control','placeholder'=>"E-mail"]])
        ->add('firstname',TextType::class, ['attr'=>['class' => 'form-control','placeholder'=>"Prénom"]])
        ->add('lastname',TextType::class, ['attr'=>['class' => 'form-control','placeholder'=>"Nom"]])
        ->add(
            'date_d_n',
            DateType::class,[
            'html5'  => false,
            'format' => 'dd-MM-yyyy']
            , ['attr'=>['class' => 'form-control js-datepicker','placeholder'=>"Date de naissance"]]
        )
        ->add('sexe', ChoiceType::class, array('choices' => array('Autre' => 'Autre','Homme' => 'Homme', 'Femme' => 'Femme')), ['attr'=>['class' => 'dropdown-menu']])
      ->add('tel_num',NumberType::class, ['attr'=>['class' => 'form-control ','placeholder'=>"GSM"]])
      ->add('adresse',TextType::class, ['attr'=>['class' => 'form-control ']])
        ->add('Sauvgarder', SubmitType::class, ['attr'=>['class' => 'btn btn-log btn-block btn-thm2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
