<?php

namespace App\Form;

use App\Entity\Emploi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label'=>'Titre de l\'emploi',
            ])
            ->add('mission', TextareaType::class,[
                'label'=>'Mission de l\'emploi',
            ])
            ->add('formation', TextareaType::class,[
                'label'=>'Formation souhaitée',
            ])
            ->add('experience', TextareaType::class,[
                'label'=>'Expérience requise',
            ])
            ->add('competence', TextareaType::class,[
                'label'=>'Compétence requis',
            ])
            ->add('type', HiddenType::class,[
               'data'=>'Appel d\'offre',
            ])
            ->add('datepost', TextType::class,[
                'label'=>false,
            ])
            ->add('datelimit', TextType::class,[
                'label'=>false,
            ])
            ->add('societyname', TextType::class,[
                'label'=>'Nom de la société',
            ])
            ->add('img', FileType::class,[
                'mapped' => false,
            ])
            ->add('contrat', EntityType::class,[
                'class'=>'App\Entity\Contrat',
                'placeholder'=>'Selectionnez le type de contrat',
                'label'=>false,
            ])
            ->add('pays', EntityType::class,[
                'class'=>'App\Entity\Pays',
                'placeholder'=>'Selectionnez le pays',
                'label'=>false,
            ])
            ->add('localite', EntityType::class,[
                'class'=>'App\Entity\Localite',
                'placeholder'=>'Selectionnez la localité',
                'label'=>false,
            ])
            ->add('categorie', EntityType::class,[
                'class'=>'App\Entity\Categorie',
                'placeholder'=>'Selectionnez la catégorie',
                'label'=>false,
            ])
            ->add('extlink', UrlType::class,[
                'label'=>'Lien externe de candidature',
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emploi::class,
        ]);
    }
}
