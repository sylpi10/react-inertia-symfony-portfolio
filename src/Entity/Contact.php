<?php

namespace App\Entity;

use App\Enum\QuoteBudget;
use App\Enum\QuoteDeadline;
use App\Enum\QuoteProjectType;
use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 12, max: 400)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    // demande de devis (page création de site) ; vides pour les messages de la page équipe
    #[ORM\Column(length: 20, nullable: true, enumType: QuoteProjectType::class)]
    private ?QuoteProjectType $projectType = null;

    #[ORM\Column(length: 20, nullable: true, enumType: QuoteBudget::class)]
    private ?QuoteBudget $budget = null;

    #[ORM\Column(length: 20, nullable: true, enumType: QuoteDeadline::class)]
    private ?QuoteDeadline $deadline = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getProjectType(): ?QuoteProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?QuoteProjectType $projectType): static
    {
        $this->projectType = $projectType;

        return $this;
    }

    public function getBudget(): ?QuoteBudget
    {
        return $this->budget;
    }

    public function setBudget(?QuoteBudget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getDeadline(): ?QuoteDeadline
    {
        return $this->deadline;
    }

    public function setDeadline(?QuoteDeadline $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }
}
