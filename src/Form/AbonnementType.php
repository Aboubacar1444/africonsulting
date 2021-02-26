<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'choices'=>[
                    'Normal' => 'Normal',
                    'Premium' => 'Premium',
                    'Premium Plus' => 'PremiumPlus',
                    'Orium' => 'Orium',
                    'Orium Plus' => 'OriumPlus',
                ],
                
                'placeholder'=>'Choisissez le type d\'abonnement',
                'label'=>false,
            ])
            ->add('isactive', HiddenType::class, [
                'data'=>'Yes',
            ])
            ->add('datedeb', TextType::class,[
                'label'=> false,
            ])
            ->add('datefin', TextType::class, [
                'label'=>false,
            ])
            ->add('user', EntityType::class, [
                'class'=>'App\Entity\User',
                'query_builder'=> function(UserRepository $ur){
                        return $ur->createQueryBuilder('u')
                            ->andWhere('u.type = :val')
                            ->setParameter('val', 'Recruteur')
                            ->orderBy('u.forename', 'ASC');
                },
                'label'=>false, 
                'placeholder'=> 'Selectionnez l\'abonné(e)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
