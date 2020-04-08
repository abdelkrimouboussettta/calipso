<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FichierRepository")
 */
class Fichier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

const TYPE = [
    'ContratOptam' => 'Contrat OPTAM',
    'IndicateursOptam' => 'Indicateurs Optam',
    'IndicateursSuiviOptam' => 'Indicateurs Suivi Optam',
];

/**
 * @var UploadedFile
 */
private $fichier;


private $typeContrat;

/**
 * @return mixed
 */
public function getTypeContrat()
{
    return $this->typeContrat;
}

/**
 * @param mixed $typeContrat
 */
public function setTypeContrat($typeContrat)
{
    $this->typeContrat = $typeContrat;
}

/**
 * @return UploadedFile
 */
public function getFichier()
{
    return $this->fichier;
}

/**
 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $fichier
 */
public function setFichier($fichier)
{
    $this->fichier = $fichier;
}
}


