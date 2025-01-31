<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la tâche'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('status', TextType::class, ['label' => 'Statut'])
            ->add('dueDate', DateType::class, ['label' => 'Date d\'échéance', 'widget' => 'single_text'])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'libelle',
                'label' => 'Projet associé'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Créer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
