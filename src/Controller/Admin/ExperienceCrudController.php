<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExperienceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Experience::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Étape du parcours')
            ->setEntityLabelInPlural('Parcours')
            ->setDefaultSort(['position' => 'ASC'])
            ->setSearchFields(['title', 'organization', 'technos']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn(8);
        yield TextField::new('title', 'Intitulé');
        yield TextField::new('organization', 'Entreprise / école');
        yield TextField::new('period', 'Période')
            ->setHelp('Affichée telle quelle : "2021/2026", "2020"…');
        yield TextEditorField::new('description', 'Missions')
            ->hideOnIndex()
            // h1 réservé au titre de la page
            ->setTrixEditorConfig(['blockAttributes' => ['heading1' => ['tagName' => 'h4']]]);
        yield TextField::new('technos', 'Technos')
            ->setHelp('Séparées par des virgules.')
            ->hideOnIndex();

        yield FormField::addColumn(4);
        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Du plus petit (en haut de la timeline) au plus grand.');
        yield AssociationField::new('projects', 'Projets liés')
            ->setHelp('Projets lancés ou repris pendant cette étape.')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
    }
}
