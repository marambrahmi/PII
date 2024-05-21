<?php

namespace App\Entity;

use App\Repository\PayementhotelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PayementhotelRepository::class)
 */
class Payementhotel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NomPrenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numtel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $methodePayement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCarte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeSecurite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prixTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrenom(): ?string
    {
        return $this->NomPrenom;
    }

    public function setNomPrenom(string $NomPrenom): self
    {
        $this->NomPrenom = $NomPrenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumtel(): ?string
    {
        return $this->numtel;
    }

    public function setNumtel(string $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getMethodePayement(): ?string
    {
        return $this->methodePayement;
    }

    public function setMethodePayement(string $methodePayement): self
    {
        $this->methodePayement = $methodePayement;

        return $this;
    }

    public function getNumCarte(): ?string
    {
        return $this->numCarte;
    }

    public function setNumCarte(string $numCarte): self
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getCodeSecurite(): ?string
    {
        return $this->codeSecurite;
    }

    public function setCodeSecurite(string $codeSecurite): self
    {
        $this->codeSecurite = $codeSecurite;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }
}
