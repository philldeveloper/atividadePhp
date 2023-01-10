<?php

namespace App\Entity;

use App\Repository\FuncionarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FuncionarioRepository::class)]
class Funcionario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'funcionario')]
    private ?Empresa $empresa = null;

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

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        // unset the owning side of the relation if necessary
        if ($empresa === null && $this->empresa !== null) {
            $this->empresa->setFuncionario(null);
        }

        // set the owning side of the relation if necessary
        if ($empresa !== null && $empresa->getFuncionario() !== $this) {
            $empresa->setFuncionario($this);
        }

        $this->empresa = $empresa;

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
