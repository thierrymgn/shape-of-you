<?php

namespace App\Form;

use App\Entity\Category;
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
                'choice_label' => fn (WardrobeStatus $wardrobeStatus) => $wardrobeStatus->name,
            ])
            ->add('season', EnumType::class, [
                'class' => WardrobeSeason::class,
                'choice_label' => fn (WardrobeSeason $wardrobeSeason) => $wardrobeSeason->name,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'Name',
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
