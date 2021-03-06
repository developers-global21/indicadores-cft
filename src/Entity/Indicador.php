<?php

namespace App\Entity;

use App\Repository\IndicadorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndicadorRepository::class)
 */
class Indicador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ente::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ente;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $formula;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnte(): ?Ente
    {
        return $this->ente;
    }

    public function setEnte(?Ente $ente): self
    {
        $this->ente = $ente;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function setFormula(?string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    // Registra el m??todo m??gico para imprimir el nombre del estado, por ejemplo, California
    public function __toString()
    {
        return $this->nombre;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
