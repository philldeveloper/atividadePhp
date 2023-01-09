<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $operation = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="O valor da Transação não pode estar vazio.")
     * @Assert\PositiveOrZero(message="O valor da Transação deve ser positivo ou zero.")
     * @Assert\Length(min=1, max=255)
     */
    #[ORM\Column]
    private ?int $value = null;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="O valor Descrição da Transação não pode estar vazia.")
     * @Assert\Length(min=3, max=255, minMessage="A Descrição da Transação precisa pelo menos 3 caracteres.")
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    private ?Account $targetAccount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTargetAccount(): ?Account
    {
        return $this->targetAccount;
    }

    public function setTargetAccount(?Account $targetAccount): self
    {
        $this->targetAccount = $targetAccount;

        return $this;
    }
}
