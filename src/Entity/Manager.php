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

    #[ORM\OneToOne(inversedBy: 'manager', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]  // , onDelete: 'CASCADE'
    /**
     * @ORM\Column(type="string")
     */
    private ?User $user = null;


    // #[ORM\OneToOne(mappedBy: 'manager', cascade: ['persist', 'remove'])]
    // #[ORM\JoinColumn(nullable: true)]
    // private ?Agency $agency = null;

    #[ORM\OneToOne(mappedBy: 'manager')]
    private ?Agency $agency = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency($agency): self
    {
        // unset the owning side of the relation if necessary
        if ($agency === null && $this->agency !== null) {
            $this->agency->setManager(null);
        }

        // set the owning side of the relation if necessary
        if ($agency !== null && $agency->getManager() !== $this) {
            $agency->setManager($this);
        }

        $this->agency = $agency;

        return $this;
    }

    public function __toString()
    {
        return $this->user->getName();
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
