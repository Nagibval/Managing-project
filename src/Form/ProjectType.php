<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, ['label' => 'Nom du projet'])
            ->add('code', TextType::class, ['label' => 'Code'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('beginDate', DateType::class, ['label' => 'Date de début', 'widget' => 'single_text'])
            ->add('endDate', DateType::class, ['label' => 'Date de fin', 'widget' => 'single_text'])
            ->add('estimateEndDate', DateType::class, ['label' => 'Date fin estimée', 'widget' => 'single_text'])
            ->add('cost', NumberType::class, ['label' => 'Coût'])
            ->add('status', TextType::class, ['label' => 'Statut', 'required' => false])
            ->add('isFinished', CheckboxType::class, [
                'label' => 'Projet terminé',
                'required' => false,
                'mapped' => true 
            ])
            ->add('isSuccess', CheckboxType::class, [
                'label' => 'Projet réussi',
                'required' => false,
                'mapped' => false
            ])
            
            
            ->add('submit', SubmitType::class, ['label' => 'Créer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
