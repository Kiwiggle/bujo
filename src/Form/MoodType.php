<?php

namespace App\Form;

use App\Entity\Mood;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feeling', ChoiceType::class, [
                'label' => "Comment t'es tu sentie aujourd'hui ? ",
                'choices' => $this->getChoices()
            ])
            ->add('gratitude', TextType::class, [
                'label' => "Qu'est ce qui t'a rendue heureuse aujourd'hui ? ",
                "required" => false
            ])
            ->add('note', TextType::class, [
                "label" => "As-tu quelque chose à ajouter ? ",
                "required" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mood::class,
        ]);
    }

    private function getChoices()
    {
        $choices = Mood::FEELING;
        $output = [];
        foreach($choices as $key => $value) {
            $output[$value] = $key;
        }
        return $output;
    }
}
