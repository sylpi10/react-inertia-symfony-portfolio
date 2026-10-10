<?php

namespace App\Entity;

use App\Enum\Audience;
use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Étape du parcours (poste, formation, diplôme), affichée dans la timeline de l'accueil.
 */
#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["experience:list"])]
    private ?int $id = null;

    // intitulé du poste ou de la formation
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(["experience:list"])]
    private ?string $title = null;

    // entreprise, école ou "Freelance"
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(["experience:list"])]
    private ?string $organization = null;

    // texte libre affiché tel quel : "2021/2026", "2020"…
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Groups(["experience:list"])]
    private ?string $period = null;

    // missions, en HTML (éditeur du back-office), version de l'accueil (page équipe) ;
    // remplacée côté création de site par getDescriptionFor() (PagePresenter)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["experience:list"])]
    private ?string $description = null;

    // version pour la page « création de site » ; vide = celle de l'accueil
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $clientDescription = null;

    // séparées par des virgules, comme pour les projets
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["experience:list"])]
    private ?string $technos = null;

    // ordre dans la timeline, du plus petit (en haut) au plus grand
    #[ORM\Column]
    private int $position = 0;

    /**
     * Projets lancés ou repris pendant cette étape. Un projet peut apparaître
     * dans plusieurs étapes (créé à un moment, modifié à un autre).
     *
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: "experiences")]
    #[ORM\OrderBy(["id" => "ASC"])]
    #[Groups(["experience:list"])]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf("%s – %s (%s)", $this->title, $this->organization, $this->period);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTechnos(): ?string
    {
        return $this->technos;
    }

    public function setTechnos(?string $technos): static
    {
        $this->technos = $technos;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addExperience($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeExperience($this);
        }

        return $this;
    }

    public function getClientDescription(): ?string
    {
        return $this->clientDescription;
    }

    public function setClientDescription(?string $clientDescription): static
    {
        $this->clientDescription = $clientDescription;

        return $this;
    }

    // texte de la page d'un public, celui de l'accueil à défaut
    public function getDescriptionFor(Audience $audience): ?string
    {
        return Audience::Client === $audience && '' !== trim(strip_tags((string) $this->clientDescription))
            ? $this->clientDescription
            : $this->description;
    }
}
