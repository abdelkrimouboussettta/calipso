<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $jourAt;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="pages")
     */
    private $categorie;
/**
     * @var UploadedFile
     */
    private $fichier;

    /**
    * @var page 
    * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="enfants")
     */
    private $parent;

    /**
    * var ArrayCollection 
    * @ORM\OneToMany(targetEntity="App\Entity\Page", mappedBy="parent")
     */
    private $enfants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="page")
     */
    private $documents;

    public function __construct()
    {
        
        $this->enfants = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    
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

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getJourAt(): ?\DateTimeInterface
    {
        return $this->jourAt;
    }

    public function setJourAt(\DateTimeInterface $jourAt): self
    {
        $this->jourAt = $jourAt;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

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
        return $this;
    }
    /**
     * @return page
     */
     

        public function getParent(): ?page
        {
            return $this->parent;
        }
    /**
    * @param page $parent
    */

        public function setParent( $parent): self
        {
            $this->parent = $parent;

            return $this;
        }

        /**
         * @return Collection|self[]
         */
        public function getEnfants(): Collection
        {
            return $this->enfants;
        }

        public function addEnfant(self $enfant): self
        {
            if (!$this->enfants->contains($enfant)) {
                $this->enfants[] = $enfant;
            }

            return $this;
        }

        public function removeEnfant(self $enfant): self
    {
        if ($this->enfants->contains($enfant)) {
            $this->enfants->removeElement($enfant);
        }

            return $this;
    }

        /**
         * @return Collection|Document[]
         */
        public function getDocuments(): Collection
        {
            return $this->documents;
        }

        public function addDocument(Document $document): self
        {
            if (!$this->documents->contains($document)) {
                $this->documents[] = $document;
                $document->setPage($this);
            }

            return $this;
        }

        public function removeDocument(Document $document): self
        {
            if ($this->documents->contains($document)) {
                $this->documents->removeElement($document);
                // set the owning side to null (unless already changed)
                if ($document->getPage() === $this) {
                    $document->setPage(null);
                }
            }

            return $this;
        }    
}

        