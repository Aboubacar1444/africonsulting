<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'constraints' => [
                    new Email(['message' => 'Entre un email correct.'])
                ]
            ])
           
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Repeter le mot de passe'),
            ])
                
            ->add('name', TextType::class,[
                'label'=>'Nom',
            ])
            ->add('forename', TextType::class,[
                'label'=>'Prénom'
            ])
            ->add('sexe', ChoiceType::class,[
                'choices'=>[
                    "Homme"=>"Homme",
                    "Femme"=>"Femme",
                    "Autre" => 'Autre',
                ],
                'placeholder'=> "Selectionnez votre sexe",
                'label'=>false,
            ])
            ->add('statutm', ChoiceType::class,[
                'choices'=>[
                    "Célibataire"=>"Célibataire",
                    "Marié(e)"=>"Marié(e)",
                    "Autre" => 'Autre',
                ],
                'placeholder'=> "Selectionnez votre état matrimonial",
                'label'=>false,
            ])
            ->add('nationalie', TextType::class,[
                'label'=>false,
            ])
            ->add('domaine', EntityType::class,[
                'class'=>'App\Entity\Domaine',
                'placeholder'=>'Selectionnez votre fonction',
                'label'=>false,
            ])
            ->add('cv', FileType::class,[
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize'=>'1500000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            
           
            ->add('type', HiddenType::class,[
                'data'=>'Candidat',
            ])
            ->add('telnumber', TextType::class,[
                'label'=>"Numéro de téléphone",
            ])
            
            ->add('localite', EntityType::class,[
                'placeholder'=>"Selectionnez votre localité",
                'class'=>'App\Entity\Localite',
                'label'=>false,
            ])
            ->add('captcha', CaptchaType::class,[
                'reload'=>true,
                'label'=>false,
                'as_url'=>true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
