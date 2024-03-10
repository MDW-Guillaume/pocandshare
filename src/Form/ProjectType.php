<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Tech;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nom du projet *',
                'required' => true,
            ])
            ->add('thumbnail', FileType::class, [
                'mapped'   => false,
                'label'    => 'Image *',
                'required' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Description *',
                'required' => true,
            ])
            ->add('link', TextType::class, [
                'label'    => 'Lien',
                'required' => false,
            ])
            ->add('date_start', null, [
                'label'    => 'Début du projet *',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_end', null, [
                'label'    => 'Fin du projet',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categorie *',
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('tech', EntityType::class, [
                'class' => Tech::class,
                'label' => 'Langages *',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
