<?php

namespace App\Form;

use App\Entity\Booking;
use Doctrine\DBAL\Types\SmallIntType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beginAt', DateTimeType::class, [
                'label' => "Début :",
                'widget' => 'single_text'
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => "Fin :",
                'widget' => 'single_text'
            ])
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'événement :'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description :'
            ])
            ->add('place', TextType::class, [
                'label' => 'Adresse :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
