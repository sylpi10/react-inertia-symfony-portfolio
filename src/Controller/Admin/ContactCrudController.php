<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Enum\QuoteBudget;
use App\Enum\QuoteDeadline;
use App\Enum\QuoteProjectType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Messages reçus par le formulaire de contact, aussi envoyés par mail ;
 * lecture seule, suppression gardée pour effacer les données d'un visiteur qui le demande.
 */
class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message')
            ->setEntityLabelInPlural('Messages')
            ->setDefaultSort(['date' => 'DESC', 'id' => 'DESC'])
            ->setSearchFields(['name', 'email', 'message']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        // n° cité dans l'alerte ntfy d'un mail non envoyé
        yield IdField::new('id', 'N°');
        yield DateField::new('date', 'Reçu le');
        yield TextField::new('name', 'Nom');
        yield EmailField::new('email', 'Email');
        yield TextareaField::new('message', 'Message')
            // extrait dans la liste, message entier sur la fiche
            ->setMaxLength(Crud::PAGE_INDEX === $pageName ? 80 : 10000);

        // demande de devis (page création de site), vide sinon
        yield ChoiceField::new('projectType', 'Type de projet')
            ->setChoices(self::labels(QuoteProjectType::cases()))
            ->hideOnIndex();
        yield ChoiceField::new('budget', 'Budget')
            ->setChoices(self::labels(QuoteBudget::cases()));
        yield ChoiceField::new('deadline', 'Délai')
            ->setChoices(self::labels(QuoteDeadline::cases()))
            ->hideOnIndex();
    }

    /**
     * Libellés des enums (label()) au lieu du nom des cas ; libellé => valeur en base,
     * des cas d'enum en valeurs feraient afficher leur nom par EasyAdmin.
     *
     * @param list<QuoteProjectType|QuoteBudget|QuoteDeadline> $cases
     *
     * @return array<string, string>
     */
    private static function labels(array $cases): array
    {
        return array_combine(
            array_map(fn ($case) => $case->label(), $cases),
            array_map(fn ($case) => $case->value, $cases),
        );
    }
}
