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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
class registerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('username',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Pseudo"]])
        ->add('email',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"E-mail"]])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,'mapped' => false,
            'invalid_message' => 'The password fields must match.',
    
            'required' => true,
            'first_options'  => ['attr' => ['class' => 'form-control','placeholder'=>"Mot de passe"]],
            'second_options' =>['attr' => ['class' => 'form-control','placeholder'=>"Confirmer Mot de passe"]],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                
                ]),
            ]
        ]
        
        )
        ->add('firstname',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Prénom"]])
        ->add('lastname',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Nom"]])
        ->add(
            'date_d_n',
            DateType::class,[
            'html5'  => false,
            'format' => 'dd-MM-yyyy']
            , ['attr'=>['class' => 'form-control js-datepicker','placeholder'=>"Date de naissance"]]
        )
        ->add('sexe', ChoiceType::class, array('choices' => array('Autre' => 'Autre','Homme' => 'Homme', 'Femme' => 'Femme')), ['attr'=>['class' => 'form-control h50','placeholder'=>"Nom"]])
      ->add('tel_num',NumberType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"GSM"]])
      ->add('adresse',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Adresse"]])
        ->add('Register', SubmitType::class, ['attr'=>['class' => 'btn btn-log btn-block btn-thm2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
