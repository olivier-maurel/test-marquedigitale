<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterielRepository::class)
 */
class Materiel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Fabricant::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fabricant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $debut_commercialisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fin_commercialisation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix_public;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_court;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference_fabricant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = intval($id);

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFabricant(): ?Fabricant
    {
        return $this->fabricant;
    }

    public function setFabricant(?Fabricant $fabricant): self
    {
        $this->fabricant = $fabricant;

        return $this;
    }

    public function getDebutCommercialisation(): ?string
    {
        return $this->debut_commercialisation;
    }

    public function setDebutCommercialisation(string $debut_commercialisation): self
    {
        $this->debut_commercialisation = $debut_commercialisation;

        return $this;
    }

    public function getFinCommercialisation(): ?string
    {
        return $this->fin_commercialisation;
    }

    public function setFinCommercialisation(?string $fin_commercialisation): self
    {
        $this->fin_commercialisation = $fin_commercialisation;

        return $this;
    }

    public function getPrixPublic(): ?float
    {
        return $this->prix_public;
    }

    public function setPrixPublic(?float $prix_public): self
    {
        $this->prix_public = $prix_public;

        return $this;
    }

    public function getNomCourt(): ?string
    {
        return $this->nom_court;
    }

    public function setNomCourt(string $nom_court): self
    {
        $this->nom_court = $nom_court;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getReferenceFabricant(): ?string
    {
        return $this->reference_fabricant;
    }

    public function setReferenceFabricant(string $reference_fabricant): self
    {
        $this->reference_fabricant = $reference_fabricant;

        return $this;
    }

    public function getSetters()
    {
        return [
            'materiel_id'               => 'setId',
            'type_id'                   => 'setType',
            'fabricant_id'              => 'setFabricant',
            'debut_commercialisation'   => 'setDebutCommercialisation',
            'fin_commercialisation'     => 'setFinCommercialisation',
            'prix_public'               => 'setPrixPublic',
            'nom_court'                 => 'setNomCourt',
            'nom'                       => 'setNom',
            'marque'                    => 'setMarque',
            'reference_fabricant'       => 'setReferenceFabricant'
        ];
    }

}
