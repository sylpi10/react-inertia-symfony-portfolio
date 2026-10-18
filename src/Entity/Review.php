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
     * Projets concernés, s'il y en a (un avis peut porter sur le travail en général) ;
     * un projet peut recevoir plusieurs avis.
     *
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'reviews')]
    #[ORM\OrderBy(['id' => \SortDirection::Ascending])]
    private Collection $projects;

    // false tant que non validé en admin
    #[ORM\Column(options: ['default' => false])]
    private bool $validated = false;

    // accord de publication donné dans le formulaire public ; vide pour un avis saisi en admin
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $consentedAt = null;

    // même avis déposé sur la fiche Google : note et lien saisis à la main en admin,
    // affichés en étoiles sur le site ; vides si le client n'a pas posté sur Google
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'La note Google va de {{ min }} à {{ max }}.')]
    private ?int $googleRating = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true)]
    #[Assert\Length(max: 255)]
    private ?string $googleReviewUrl = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->author ?? '';
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
            $project->addReview($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeReview($this);
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

    public function getGoogleRating(): ?int
    {
        return $this->googleRating;
    }

    public function setGoogleRating(?int $googleRating): static
    {
        $this->googleRating = $googleRating;

        return $this;
    }

    public function getGoogleReviewUrl(): ?string
    {
        return $this->googleReviewUrl;
    }

    public function setGoogleReviewUrl(?string $googleReviewUrl): static
    {
        $this->googleReviewUrl = $googleReviewUrl;

        return $this;
    }
}
