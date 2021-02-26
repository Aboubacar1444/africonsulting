<?php

namespace App\Form;

use App\Entity\Domaine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class,[
            'label'=>'Nom du domaine',
        ])
        ->add('categorie', EntityType::class,[
            'class'=>'App\Entity\Categorie',
            'placeholder'=>'Selectionnez la catégorie',
            'label'=>false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Domaine::class,
        ]);
    }
}
