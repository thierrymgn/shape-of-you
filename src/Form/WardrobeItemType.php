<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\WardrobeStatus;
use App\Enum\WardrobeSeason;


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
                'choice_label' => fn (WardrobeStatus $wardrobeStatus) => $wardrobeStatus->getStatuses(),

            ])
            ->add('season', EnumType::class, [
                'class' => WardrobeSeason::class,
                'choice_label' => fn (WardrobeSeason $wardrobeSeason) => $wardrobeSeason->getSeasons(),
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('customer', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
'choice_label' => 'id',
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
