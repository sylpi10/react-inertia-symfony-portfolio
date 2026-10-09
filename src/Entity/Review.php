<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Avis laissé sur un ou plusieurs projets, publié une fois validé dans le back-office.
 */
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $author = null;

    // affiché sous le nom : poste, entreprise ("Gérante, La cuisine de Maha")
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100)]
    private ?string $authorRole = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $text = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /**
     * Projets concernés un projet n'a qu'un avis (côté inverse, géré par Project).
     *
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: "review")]
    #[ORM\OrderBy(["id" => "ASC"])]
    #[
        Assert\Count(
            min: 1,
            minMessage: "Un avis doit porter sur au moins un projet.",
        ),
    ]
    private Collection $projects;

    // false tant que non validé en admin
    #[ORM\Column(options: ["default" => false])]
    private bool $validated = false;

    // accord de publication donné dans le formulaire public ; vide pour un avis saisi en admin
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $consentedAt = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->author ?? "";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthorRole(): ?string
    {
        return $this->authorRole;
    }

    public function setAuthorRole(?string $authorRole): static
    {
        $this->authorRole = $authorRole;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
            $project->setReview($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        // le projet a pu être rattaché entre-temps à un autre avis
        if (
            $this->projects->removeElement($project) &&
            $project->getReview() === $this
        ) {
            $project->setReview(null);
        }

        return $this;
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }

    public function getConsentedAt(): ?\DateTimeImmutable
    {
        return $this->consentedAt;
    }

    public function setConsentedAt(?\DateTimeImmutable $consentedAt): static
    {
        $this->consentedAt = $consentedAt;

        return $this;
    }
}
