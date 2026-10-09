<?php

namespace App\Entity;

use App\Enum\Audience;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["project:list", "project:detail"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[
        Groups([
            "project:list",
            "project:detail",
            "experience:list",
            "offer:list",
        ]),
    ]
    private string $name;

    // segment d'URL (/projets/{slug}), généré depuis le nom à la création
    #[ORM\Column(length: 100, unique: true)]
    #[
        Groups([
            "project:list",
            "project:detail",
            "experience:list",
            "offer:list",
        ]),
    ]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $technos = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $weblink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $githublink = null;

    #[ORM\Column(length: 255)]
    #[Groups(["project:detail"])]
    private ?string $detailPic = null;

    #[ORM\Column(length: 255)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $background = null;

    // description longue de l'accueil (page équipe) ; remplacée côté création
    // de site par getDescriptionFor() (ProjectsController)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["project:detail"])]
    private ?string $description = null;

    // version pour la page « création de site » ; vide = celle de l'accueil
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $clientDescription = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["project:detail"])]
    private ?string $detail_pic_mobile = null;

    // ordre d'affichage sur la page « création de site », du plus petit au plus grand
    #[ORM\Column]
    private int $position = 0;

    // ordre d'affichage sur l'accueil (page équipe) et pour précédent/suivant
    #[ORM\Column]
    private int $teamPosition = 0;

    /**
     * Étapes du parcours liées au projet (côté inverse, géré par Experience).
     *
     * @var Collection<int, Experience>
     */
    #[ORM\ManyToMany(targetEntity: Experience::class, mappedBy: "projects")]
    private Collection $experiences;

    // texte court des cartes projet, version de l'accueil (page équipe) ;
    // remplacé côté création de site par getMiniDescriptionFor() (PagePresenter)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["project:list"])]
    private ?string $miniDescription = null;

    // version pour la page « création de site » ; vide = celle de l'accueil
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $miniClientDescription = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $pagespeedCapture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["project:list", "project:detail"])]
    private ?string $perfText = null;

    #[ORM\Column]
    #[Groups(["project:list", "project:detail"])]
    private ?bool $auditMade = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["project:list", "project:detail"])]
    private ?\DateTime $lastAuditDate = null;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? "";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTechnos(): ?string
    {
        return $this->technos;
    }

    public function setTechnos(string $technos): static
    {
        $this->technos = $technos;

        return $this;
    }

    public function getWeblink(): ?string
    {
        return $this->weblink;
    }

    public function setWeblink(?string $weblink): static
    {
        $this->weblink = $weblink;

        return $this;
    }

    public function getGithublink(): ?string
    {
        return $this->githublink;
    }

    public function setGithublink(?string $githublink): static
    {
        $this->githublink = $githublink;

        return $this;
    }

    public function getDetailPic(): ?string
    {
        return $this->detailPic;
    }

    public function setDetailPic(string $detailPic): static
    {
        $this->detailPic = $detailPic;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(string $background): static
    {
        $this->background = $background;

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

    public function getDetailPicMobile(): ?string
    {
        return $this->detail_pic_mobile;
    }

    public function setDetailPicMobile(?string $detail_pic_mobile): static
    {
        $this->detail_pic_mobile = $detail_pic_mobile;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->addProject($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            $experience->removeProject($this);
        }

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

    public function getTeamPosition(): int
    {
        return $this->teamPosition;
    }

    public function setTeamPosition(int $teamPosition): static
    {
        $this->teamPosition = $teamPosition;

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

    public function hasClientDescription(): bool
    {
        return "" !== trim(strip_tags((string) $this->clientDescription));
    }

    // texte de la page d'un public, celui de l'accueil à défaut
    public function getDescriptionFor(Audience $audience): ?string
    {
        return Audience::Client === $audience && $this->hasClientDescription()
            ? $this->clientDescription
            : $this->description;
    }

    public function getMiniDescription(): ?string
    {
        return $this->miniDescription;
    }

    public function setMiniDescription(?string $miniDescription): static
    {
        $this->miniDescription = $miniDescription;

        return $this;
    }

    public function getMiniClientDescription(): ?string
    {
        return $this->miniClientDescription;
    }

    public function setMiniClientDescription(
        ?string $miniClientDescription,
    ): static {
        $this->miniClientDescription = $miniClientDescription;

        return $this;
    }

    // texte court de la page d'un public, celui de l'accueil à défaut
    public function getMiniDescriptionFor(Audience $audience): ?string
    {
        return Audience::Client === $audience &&
            "" !== trim(strip_tags((string) $this->miniClientDescription))
            ? $this->miniClientDescription
            : $this->miniDescription;
    }

    public function getPagespeedCapture(): ?string
    {
        return $this->pagespeedCapture;
    }

    public function setPagespeedCapture(?string $pagespeedCapture): static
    {
        $this->pagespeedCapture = $pagespeedCapture;

        return $this;
    }

    public function getPerfText(): ?string
    {
        return $this->perfText;
    }

    public function setPerfText(?string $perfText): static
    {
        $this->perfText = $perfText;

        return $this;
    }

    public function isAuditMade(): ?bool
    {
        return $this->auditMade;
    }

    public function setAuditMade(bool $auditMade): static
    {
        $this->auditMade = $auditMade;

        return $this;
    }

    public function getLastAuditDate(): ?\DateTime
    {
        return $this->lastAuditDate;
    }

    public function setLastAuditDate(?\DateTime $lastAuditDate): static
    {
        $this->lastAuditDate = $lastAuditDate;

        return $this;
    }
}
