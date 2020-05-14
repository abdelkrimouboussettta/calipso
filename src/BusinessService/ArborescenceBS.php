<?php

namespace App\BusinessService;

use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArborescenceBS
{
    /**
     * @var array
     */
    private $arbre = [];

    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Création de l'arbre des page
     *
     * @return array
     */
    public function arbre() {

        // Récupération des pages sans page parent (
        $pagesSansParent = $this->pageRepository->findPagesSansParent();

        // On boucle sur les pages top niveau
        foreach ($pagesSansParent as $page) {

            $this->branche($page);

        }

        return $this->arbre;
    }

    /**
     * On explore une branche. Cette méthode est récursive. Elle s'appelle elle-même. Elle parcoure tout l'arbre de page en page.
     *
     * @param Page $page
     * @param $niveau
     */
    private function branche(Page $page, $niveau = 0): void
    {
        $this->arbre[] = [
            'niveau' => $niveau,
            'page' => $page,
        ];

        // On parcoure les pages enfants de la page courante
        foreach ($page->getEnfants() as $pageEnfant) {

            // Pour chaque page enfant, on va également parcourir les sous pages.
            $this->branche($pageEnfant, $niveau++);
        }
    }
}
