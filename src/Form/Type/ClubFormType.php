<?php

namespace App\Form\Type;

use App\Form\Model\ClubDto;
use App\Form\Type\PlayerFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ClubFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('budget', TextType::class)
            ->add('coach', TextType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => CoachFormType::class
            ])
            ->add('players', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => PlayerFormType::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClubDto::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}