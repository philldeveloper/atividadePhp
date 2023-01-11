<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Timestampable\Traits\TimestampableEntity;

enum STATUS_CONTA: int
{
    case INATIVO = 0;
    case ATIVO = 1;
    case AGUARDANDO_ENCERRAMENTO = 2;
    case ENCERRADA = 3;
}

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    public function __construct()
    {
        $this->status = STATUS_CONTA::ATIVO->value;
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->clients = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="O valor Número da Conta não pode estar vazio.")
     * @Assert\Length(min=4, minMessage="O valor Número da Conta deverá ser de no mínimo 4 números.")
     * @Assert\PositiveOrZero(message="O valor Número da Conta deve ser positivo ou zero.")
     */
    #[ORM\Column(length: 255)]
    private ?int $number = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="O valor Saldo não pode estar vazio.")
     * @Assert\PositiveOrZero(message="O valor Saldo deve ser positivo ou zero.")
     * @Assert\Length(min=1, max=255)
     */
    #[ORM\Column(length: 255)]
    private ?int $balance = null;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="O valor Tipo de Conta não pode estar vazio.")
     * @Assert\Length(min=1, max=255)
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'account')]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @ORM\JoinColumn(nullable="false")
     * @Assert\NotBlank(message="O valor Agência da Conta não pode estar vazio.")
     */
    private ?Agency $agency = null;

    #[ORM\ManyToMany(targetEntity: Client::class, mappedBy: 'accounts', fetch: "EAGER")] //inserir depois--> nullable: false
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function setClients(ArrayCollection $client): self
    {
        return $this;
    }

    public function addClient(Client $client): self
    {

        if (!$this->clients->contains($client)) {

            $this->clients->add($client);
            // $this->clients[] = $client;

            $client->addAccount($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            $client->removeAccount($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->number;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccount() === $this) {
                $transaction->setAccount(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }
}
