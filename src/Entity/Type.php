<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Metier::class, inversedBy="types")
     * @ORM\JoinColumn(nullable=false)
     */
    private $metier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $famille;

    /**
     * @ORM\Column(type="boolean")
     */
    private $serialisable;

    /**
     * @ORM\Column(name="`option`", type="boolean")
     */
    private $option;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(name="`ordre`", type="integer")
     */
    private $ordre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="type_id", orphanRemoval=true)
     */
    private $materiels;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }

    public function __toString() {
        return $this->famille;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = intval($id);

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): self
    {
        $this->metier = $metier;

        return $this;
    }    

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(string $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    public function getSerialisable(): ?bool
    {
        return $this->serialisable;
    }

    public function setSerialisable(bool $serialisable): self
    {
        $this->serialisable = $serialisable;

        return $this;
    }

    public function getOption(): ?bool
    {
        return $this->option;
    }

    public function setOption(bool $option): self
    {
        $this->option = $option;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels[] = $materiel;
            $materiel->setTypeId($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getTypeId() === $this) {
                $materiel->setTypeId(null);
            }
        }

        return $this;
    }

    public function getSetters()
    {
        return [
            'type_id'       => 'setId',
            'metier_id'     => 'setMetier',
            'famille'       => 'setFamille',
            'serialisable'  => 'setSerialisable',
            'option'        => 'setOption',
            'nom'           => 'setNom',
            'ordre'         => 'setOrdre',
            'image'         => 'setImage',
            'color'         => 'setColor'
        ];
    }

}
