<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', TextType::class,[
                'label'=>"Thème",
            ])
            ->add('module', TextareaType::class,[
                'label'=>'Modules',
            ])
            ->add('duree', TextType::class,[
                'label' =>'Durée',
            ])
            ->add('datepost', TextType::class,[
                'label'=>false,
            ])
            ->add('datelimit', TextType::class,[
                'label'=>false,
            ])
            ->add('telnumber',TextType::class, [
                'label'=>'Contact',
            ])
            ->add('isactive', HiddenType::class,[
                'data'=>'Yes',
            ])
            ->add('img', FileType::class,[
                'mapped' => false,
                
            ])
            ->add('multiimg', FileType::class,[
                'mapped' => false,
                'multiple'=>true,
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
