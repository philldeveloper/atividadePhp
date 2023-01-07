<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->accounts = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="O valor Nome do Cliente não pode estar vazio.")
     * @Assert\Length(min=3, max=255, minMessage="O nome do Cliente precisa pelo menos 3 caracteres.")
     */
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Account::class, inversedBy: 'clients', fetch: 'EAGER')]
    private Collection $accounts;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="O valor Endereço do Cliente não pode estar vazio.")
     * @Assert\Length(min=3, max=255, minMessage="O endereço do Cliente precisa pelo menos 3 caracteres.")
     */
    private ?string $address = null;

    #[ORM\Column]
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="O valor Telefone do Cliente não pode estar vazio.")
     * @Assert\Length(min=9, minMessage="O valor Telefone do Cliente deverá ser de no mínimo 9 números.")
     * @Assert\PositiveOrZero(message="O valor Telefone do Cliente deve ser positivo ou zero.")
     */
    private ?int $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        $this->accounts->removeElement($account);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
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
            // $this->transactions[] = $transaction;

            $transaction->setClient($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClient() === $this) {
                $transaction->setClient(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
