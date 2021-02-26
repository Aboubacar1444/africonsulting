<?php

namespace App\Form;

use App\Entity\Ads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class,[
                'label'=>'Description',
            ])
            ->add('img', FileType::class,[
                'label'=>false,
                'mapped'=>false,
            ])
            ->add('type', ChoiceType::class,[
                'placeholder'=>'Selectionnez le type',
                'choices'=>[
                    'Publicité'=>'Publicité',
                    'Partenariat'=>'Partenariat',
                ],
                'label'=>false,
                
            ])
            ->add('extlink', UrlType::class,[
                'required'=>false,
            ])
            ->add('datefin', TextType::class, [
                'required'=>false,
                'label'=> false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
