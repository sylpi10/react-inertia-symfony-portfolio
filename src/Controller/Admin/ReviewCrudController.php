<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
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
            ->setSearchFields(['author', 'authorRole', 'text']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(BooleanFilter::new('validated', 'Validé'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn(8);
        yield TextField::new('author', 'Auteur');
        yield TextField::new('authorRole', 'Poste / entreprise')
            ->setHelp('Facultatif, affiché sous le nom.');
        yield TextareaField::new('text', 'Avis')
            ->setMaxLength(120);

        yield FormField::addColumn(4);
        // case à cocher directement dans la liste pour valider en un clic
        yield BooleanField::new('validated', 'Validé')
            ->setHelp('Seuls les avis validés sont affichés sur le site.');
        yield AssociationField::new('projects', 'Projets')
            ->setHelp('Facultatif : vide pour un avis sur le travail en général.')
            ->setRequired(false)
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
        yield DateTimeField::new('createdAt', 'Reçu le')
            ->hideOnForm();
        // preuve de l'accord de publication, donné dans le formulaire public
        yield DateTimeField::new('consentedAt', 'Publication acceptée le')
            ->hideOnIndex()
            ->setDisabled();

        yield FormField::addFieldset('Avis Google')
            ->setHelp('Si le client a aussi posté sur la fiche Google : recopier sa note et le lien de son avis (menu ⋮ de l\'avis → Partager). Les étoiles s\'affichent alors sur le site.');
        yield ChoiceField::new('googleRating', 'Note Google')
            ->setChoices(['5 étoiles' => 5, '4 étoiles' => 4, '3 étoiles' => 3, '2 étoiles' => 2, '1 étoile' => 1])
            ->setRequired(false);
        yield UrlField::new('googleReviewUrl', 'Lien de l\'avis Google')
            ->hideOnIndex();
    }
}
