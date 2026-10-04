<?php

namespace App\Entity;

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
    #[Groups(["project:list", "project:detail", "experience:list", "offer:list"])]
    private string $name;

    // segment d'URL (/projets/{slug}), généré depuis le nom à la création
    #[ORM\Column(length: 100, unique: true)]
    #[Groups(["project:list", "project:detail", "experience:list", "offer:list"])]
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["project:detail"])]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["project:detail"])]
    private ?string $detail_pic_mobile = null;

    // ordre d'affichage, du plus petit au plus grand
    #[ORM\Column]
    private int $position = 0;

    /**
     * Étapes du parcours liées au projet (côté inverse, géré par Experience).
     *
     * @var Collection<int, Experience>
     */
    #[ORM\ManyToMany(targetEntity: Experience::class, mappedBy: "projects")]
    private Collection $experiences;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
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
}
