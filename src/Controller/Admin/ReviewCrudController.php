<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Avis')
            ->setEntityLabelInPlural('Avis')
            // les plus récents (donc ceux à relire) en haut
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['author', 'text']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(BooleanFilter::new('validated', 'Validé'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn(8);
        yield TextField::new('author', 'Auteur');
        yield TextareaField::new('text', 'Avis')
            ->setMaxLength(120);

        yield FormField::addColumn(4);
        // case à cocher directement dans la liste pour valider en un clic
        yield BooleanField::new('validated', 'Validé')
            ->setHelp('Seuls les avis validés sont affichés sur le site.');
        yield AssociationField::new('projects', 'Projets')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
        yield DateTimeField::new('createdAt', 'Reçu le')
            ->hideOnForm();
    }
}
