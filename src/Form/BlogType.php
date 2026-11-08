<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_pl', TextType::class, [
                'label' => 'blog.title_pl',
                'required' => true,
            ])
            ->add('content_pl', TextareaType::class, [
                'label' => 'blog.content_pl',
                'required' => true,
            ])
            ->add('title_en', TextType::class, [
                'label' => 'blog.title_en',
                'required' => true,
            ])
            ->add('content_en', TextareaType::class, [
                'label' => 'blog.content_en',
                'required' => true,
            ])
            ->add('title_de', TextType::class, [
                'label' => 'blog.title_de',
                'required' => true,
            ])
            ->add('content_de', TextareaType::class, [
                'label' => 'blog.content_de',
                'required' => true,
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'blog.public',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}