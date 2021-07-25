<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Type as Types;
use App\Entity\Fabricant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaterielFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class,[
                'required'      => false,
                'label'         => false,
                'attr'          => [
                    'placeholder'   => 'Recherche...',
                    'class'         => 'form-control my-3',
                    'style'         => 'width: 415px;'
                ]
            ])
            ->add('famille', EntityType::class,[
                'class'         => Types::class,
                'placeholder'   => 'Famille',
                'label'         => false,
                'mapped'        => true,
                'required'      => false,
                'attr'          => [
                    'class' => 'form-control mx-2',
                    'style' => 'width: 200px; cursor: pointer;'
                ]
            ])
            ->add('marque', EntityType::class,[
                'class'         => Fabricant::class,
                'placeholder'   => 'Marque',
                'label'         => false,
                'mapped'        => true,
                'required'      => false,
                'attr'          => [
                    'class' => 'form-control mx-2',
                    'style' => 'width: 200px; cursor: pointer;'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
