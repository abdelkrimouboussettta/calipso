<?php

namespace App\Controller;

use App\Entity\Page;
use App\BusinessService\ArborescenceBS;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    /**
     * @Route("/page/index", name="parent.index")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
     public function index(Request $request, ArborescenceBS $arborescenceBS)
    {
        $arborescences = $arborescenceBS->arbre();

        //dump($arborescence);
        
        return $this->render('page/index.html.twig', [

                'arborescences'=>$arborescences
            ]);
    }

}
