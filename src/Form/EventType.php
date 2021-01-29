<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Restriction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "Nom de l'Événement",
                ]
            ])
            ->add('date_start', DateTimeType::class, [
                'label' => "Date/Heure de Début",
                'widget' => 'single_text'
            ])
            ->add('date_end', DateTimeType::class, [
                'label' => "Date/Heure de Fin",
                'widget' => 'single_text'
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation',
                'attr' => [
                    'placeholder' => "Le lieu de l'Événement",
                    'id' => 'searchBox'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => "La description de l'Événement"
                ]
            ])
            ->add('nb_participants', IntegerType::class, [
                'label' => "Nombre de particpants",
                'attr' => [
                    'placeholder' => "Le nombre de particpants à l'Événement",
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => "Prix",
                'attr' => [
                    'placeholder' => "Le prix de l'Événement",
                ]
            ])
            ->add('restrictions', EntityType::class, [
                'label' => 'Restriction(s)',
                'class' => Restriction::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
