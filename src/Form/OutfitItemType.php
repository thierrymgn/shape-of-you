<?php

namespace App\Form;

use App\Entity\OutfitItem;
use App\Entity\WardrobeItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutfitItemType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wardrobeItem', EntityType::class, [
                'class' => WardrobeItem::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('w')
                        ->where('w.customer = :user')
                        ->setParameter('user', $this->security->getUser());
                },
                'label' => 'Vêtement',
                'required' => true,
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OutfitItem::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'outfit_item',
        ]);
    }
}
