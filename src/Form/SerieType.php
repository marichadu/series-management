<?php
namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('overview', TextareaType::class, [
                'label' => 'Overview',
                'attr' => ['class' => 'form-control']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Ended' => 'ended',
                    'Returning' => 'returning',
                    'Canceled' => 'canceled',
                ],
                'placeholder' => 'Select a status',
                'data' => 'returning', // Default value
                'attr' => ['class' => 'form-control']
            ])
            ->add('vote', NumberType::class, [
                'label' => 'Vote',
                'attr' => ['class' => 'form-control']
            ])
            ->add('popularity', NumberType::class, [
                'label' => 'Popularity',
                'attr' => ['class' => 'form-control']
            ])
            ->add('genres', ChoiceType::class, [
                'label' => 'Genres',
                'choices' => [
                    'Drama' => 'Drama',
                    'Comedy' => 'Comedy',
                    'Sci-Fi' => 'Sci-Fi',
                    'Fantasy' => 'Fantasy',
                    'Crime' => 'Crime',
                    'Family' => 'Family',
                ],
                'placeholder' => 'Select a genre',
                'multiple' => false, // Allow multiple selections if needed
                'expanded' => false, // Render as a dropdown
                'attr' => ['class' => 'form-control']
            ])
            ->add('first_air_date', DateType::class, [
                'label' => 'First Air Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('last_air_date', DateType::class, [
                'label' => 'Last Air Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('posterFile', FileType::class, [
                'label' => 'Poster (JPEG or PNG)',
                'required' => false,
                'mapped' => false, // This field is not mapped to the database
                'attr' => ['class' => 'form-control']
            ])
            ->add('tmdb_id', NumberType::class, [
                'label' => 'TMDB ID',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
} 