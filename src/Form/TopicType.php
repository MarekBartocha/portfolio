<?php

namespace App\Form;

use App\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_pl', TextType::class, [
                'label' => 'topic.name_pl',
                'required' => true,
            ])
            ->add('name_en', TextType::class, [
                'label' => 'topic.name_en',
                'required' => true,
            ])
            ->add('name_de', TextType::class, [
                'label' => 'topic.name_de',
                'required' => true,
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'topic.public',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
