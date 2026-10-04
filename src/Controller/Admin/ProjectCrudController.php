<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Service\ProjectImageStorage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Image;

class ProjectCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ProjectImageStorage $imageStorage,
        private readonly SluggerInterface $slugger,
    ) {}

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projets')
            ->setDefaultSort(['id' => 'ASC'])
            ->setSearchFields(['name', 'technos']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield FormField::addColumn(8);
        yield TextField::new('name', 'Nom');
        // généré depuis le nom à la création, puis figé : le changer casserait les URLs
        yield TextField::new('slug', 'URL')
            ->hideOnForm();
        yield TextField::new('slug', 'URL (/projets/…)')
            ->onlyWhenUpdating()
            ->setDisabled()
            ->setHelp('Fixée à la création pour ne pas casser les liens existants.');
        yield TextField::new('date', 'Année')->hideOnIndex();
        yield TextField::new('technos', 'Technos')
            ->setHelp('Séparées par des virgules.')
            ->hideOnIndex();
        yield TextEditorField::new('description', 'Description')
            ->hideOnIndex()
            // le bouton "titre" de l'éditeur produit un h2 : le h1 est le nom du projet
            ->setTrixEditorConfig(['blockAttributes' => ['heading1' => ['tagName' => 'h2']]])
            ->setNumOfRows(20);
        yield UrlField::new('weblink', 'Site')->hideOnIndex();
        yield UrlField::new('githublink', 'GitHub')->hideOnIndex();

        yield FormField::addColumn(4);
        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Du plus petit (affiché en premier) au plus grand.');
        // côté inverse de la relation : by_reference=false pour passer par
        // addExperience()/removeExperience(), qui mettent à jour Experience
        yield AssociationField::new('experiences', 'Étapes du parcours')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false)
            ->hideOnIndex();
        yield $this->imageField('background', 'Vignette (accueil)', $pageName);
        yield $this->imageField('detailPic', 'Capture desktop (pleine page)', $pageName)->hideOnIndex();
        yield $this->imageField('detail_pic_mobile', 'Capture mobile', $pageName, required: false)->hideOnIndex();
    }

    private function imageField(string $property, string $label, string $pageName, bool $required = true): ImageField
    {
        return ImageField::new($property, $label)
            ->setBasePath(ProjectImageStorage::BASE_PATH)
            ->setUploadDir(ProjectImageStorage::UPLOAD_DIR)
            ->setUploadedFileNamePattern(
                fn (UploadedFile $file): string => sprintf(
                    '%s-%s.webp',
                    $this->slugger->slug(pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME))->lower(),
                    bin2hex(random_bytes(4)),
                ),
            )
            ->setFormTypeOption('upload_new', $this->imageStorage->store(...))
            // un même fichier peut servir à deux champs (vignette et capture) :
            // ne jamais supprimer l'ancien au remplacement
            ->keepReplacedFile()
            ->setFileConstraints(new Image(maxSize: '10M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp']))
            ->setRequired($required && Crud::PAGE_NEW === $pageName);
    }
}
