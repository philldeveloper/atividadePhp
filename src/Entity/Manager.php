<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager
{
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="O valor Nome do Gerente não pode estar vazio.")
     * @Assert\Length(min=3, max=255, minMessage="O nome do Gerente precisa pelo menos 3 caracteres.")
     */
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'manager', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @ORM\Column(type="string")
     */
    private ?User $user = null;


    #[ORM\OneToOne(mappedBy: 'manager', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agency $agency = null;


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

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(Agency $agency): self
    {
        // set the owning side of the relation if necessary
        if ($agency->getManager() !== $this) {
            $agency->setManager($this);
        }

        $this->agency = $agency;

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
}
