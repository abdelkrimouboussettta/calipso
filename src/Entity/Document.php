<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="documents")
     */
    private $page;

    /**
     * @var UploadedFile
    */
    private $fichier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
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
