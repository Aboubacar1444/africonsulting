<?php

namespace App\Form;

use App\Entity\Localite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom de la localité',
            ])
            ->add('pays', EntityType::class,[
                'class'=>'App\Entity\Pays',
                'placeholder'=>'Selectionnez le pays',
                'label'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Localite::class,
        ]);
    }
}
