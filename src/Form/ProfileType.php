<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('height', NumberType::class, [
                'label' => 'Taille (cm)',
                'required' => false,
                'scale' => 2,
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids (kg)',
                'required' => false,
                'scale' => 2,
            ])
            ->add('bodyType', ChoiceType::class, [
                'label' => 'Type de morphologie',
                'required' => false,
                'choices' => [
                    'Ectomorphe' => 'ectomorphe',
                    'Mesomorphe' => 'mesomorphe',
                    'Endomorphe' => 'endomorphe',
                    'Triangle' => 'triangle',
                    'Triangle inversé' => 'triangle inversé',
                    'Sablier' => 'sablier',
                    'Rectangle' => 'rectangle',
                ],
            ])
            ->add('stylePreferences', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'label' => 'Styles préférés',
                'entry_options' => [
                    'attr' => ['class' => 'form-control'],
                ],
            ])
            ->add('colorPreferences', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'label' => 'Couleurs préférées',
                'entry_options' => [
                    'attr' => ['class' => 'form-control'],
                ],
            ]);

        $sizePreferences = $builder->getData()->getSizePreferences() ?? [];

        $builder->add('topSize', TextType::class, [
            'label' => 'Haut',
            'required' => false,
            'mapped' => false,
            'data' => $sizePreferences['haut'] ?? null,
        ]);

        $builder->add('bottomSize', TextType::class, [
            'label' => 'Bas',
            'required' => false,
            'mapped' => false,
            'data' => $sizePreferences['bas'] ?? null,
        ]);

        $builder->add('shoeSize', TextType::class, [
            'label' => 'Chaussures',
            'required' => false,
            'mapped' => false,
            'data' => $sizePreferences['chaussures'] ?? null,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
