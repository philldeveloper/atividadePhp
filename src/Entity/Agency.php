<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{
    
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->account = new ArrayCollection();
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
        * @ORM\Column(type="integer")
        * @Assert\NotBlank(message="O valor Número da Agência não pode estar vazio.")
        * @Assert\Length(min=4, minMessage="O valor Número da Agência deverá ser de no mínimo 4 números.")
        * @Assert\PositiveOrZero(message="O valor Número da Agência deve ser positivo ou zero.")
    */
    #[ORM\Column(length: 255)]
    private ?string $number = null;

    /**
        * @Assert\NotBlank(message="O valor Banco não pode estar vazio.")
    */
    #[ORM\ManyToOne(inversedBy: 'agencies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Bank $bank = null;

    /**
        * @Assert\NotBlank(message="O valor Gerente da Agência não pode estar vazio.")
    */
    #[ORM\OneToOne(inversedBy: 'agency', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Manager $manager = null;

    /**
        * @ORM\Column(type="string")
        * @Assert\NotBlank(message="O valor Endereço da Agência não pode estar vazio.")
        * @Assert\Length(min=4, minMessage="O valor Endereço da Agência deverá ser de no mínimo 4 caracteres.")
    */
    #[ORM\Column(length: 255)]
    private ?string $address = null;


    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Account::class, orphanRemoval: true)]
    private Collection $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(Manager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccount(): Collection
    {
        return $this->account;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->account->contains($account)) {
            $this->account->add($account);
            $account->setAgency($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->account->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getAgency() === $this) {
                $account->setAgency(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->number;
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
}
