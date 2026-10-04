<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Service')
            ->setEntityLabelInPlural('Services')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn(8);
        yield TextField::new('title', 'Titre');
        yield TextEditorField::new('description', 'Texte')
            ->setHelp('Deux ou trois phrases : le besoin du client et ce que vous lui apportez.')
            ->hideOnIndex();

        yield FormField::addColumn(4);
        yield IntegerField::new('position', 'Ordre');
        yield AssociationField::new('projects', 'Projets en exemple')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
    }
}
