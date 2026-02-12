<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])
            ->add('description', null, ['required' => false, 'label' => 'Description'])
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'constraints' => [new LessThanOrEqual(['value' => 'today', 'message' => 'Pas de date future'])]
            ])
            ->add('videoId', null, ['label' => 'ID Vidéo'])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name'
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Formation::class]);
    }
}