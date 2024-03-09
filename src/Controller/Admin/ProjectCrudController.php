<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {        
        yield FormField::addTab('Général');
        yield IdField::new('id')->hideOnForm();
        yield ArrayField::new('user', 'Utilisateur')->hideOnIndex();
        yield AssociationField::new('user', 'Utilisateur');
        yield TextField::new('name');

        yield FormField::addTab('Catégories');
        yield ArrayField::new('tech', 'Technologies');
        yield AssociationField::new('tech', 'Technologies')
        ->setFormTypeOption('multiple', true)
        ->hideOnIndex();
        yield ArrayField::new('category', 'Catégorie');
        yield AssociationField::new('category', 'Catégorie');

        yield FormField::addTab('Détails');
        yield TextEditorField::new('description')->hideOnIndex();
        yield ImageField::new('thumbnail')
        ->setUploadDir('public/images')
        // ->setBasePath('public/images')
        ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
        
        yield FormField::addTab('Dates');
        yield DateField::new('date_start')->hideOnIndex();
        yield DateField::new('date_end')->hideOnIndex();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
