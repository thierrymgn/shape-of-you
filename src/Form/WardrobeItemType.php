<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WardrobeItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('brand')
            ->add('size')
            ->add('color')
            ->add('image')
            ->add('status', EnumType::class, [
                'class' => WardrobeStatus::class,
                'choice_label' => fn (WardrobeStatus $wardrobeStatus) => $wardrobeStatus->name, // ou getLabel() si défini
            ])
            ->add('season', EnumType::class, [
                'class' => WardrobeSeason::class,
                'choice_label' => fn (WardrobeSeason $wardrobeSeason) => $wardrobeSeason->name, // ou getLabel() si défini
            ])
            ->add('customer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WardrobeItem::class,
        ]);
    }
}
