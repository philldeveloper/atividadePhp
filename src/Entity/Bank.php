<?php

namespace App\Entity;

use App\Repository\BankRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BankRepository::class)]
class Bank
{
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->agencies = new ArrayCollection();
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
        * @ORM\Column(type="string")
        * @Assert\NotBlank(message="O valor Nome do Banco não pode estar vazio.")
        * @Assert\Length(min=3, max=255, minMessage="O nome do Banco precisa pelo menos 3 caracteres.")
    */
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'bank', targetEntity: Agency::class)]
    private Collection $agencies;



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
     * @return Collection<int, Agency>
     */
    public function getAgencies(): Collection
    {
        return $this->agencies;
    }

    public function addAgency(Agency $agency): self
    {
        if (!$this->agencies->contains($agency)) {
            $this->agencies->add($agency);
            $agency->setBank($this);
        }

        return $this;
    }

    public function removeAgency(Agency $agency): self
    {
        if ($this->agencies->removeElement($agency)) {
            // set the owning side to null (unless already changed)
            if ($agency->getBank() === $this) {
                $agency->setBank(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
