<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class FrontofficeController extends AbstractController
{
    /**
     * @Route("/", name="frontoffice")
     */
    public function index()

    {
        $repo = $this->getDoctrine()->getRepository(Page::class);
        $page = $repo-> findLastPages();

        return $this->render('frontoffice/index.html.twig', [
            'controller_name' => 'FrontofficeController',
            'pages'            => $page,
        ]);
    }
      
      
    /**
     * @Route("/page2", name="FRINTOFFICE-PAGE2")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // les 3 dernieres pages
    public function lastpage(Request $request)
    {
        
        $repo = $this->getDoctrine()->getRepository(Page::class);
        $page = $repo-> findLastPages();
        
    //  dump($page);die;
    
        return $this->render('frontoffice/page2.html.twig', [
            'controller_name' => 'FrontofficeController',
            'pages'            => $pages,
       
        ]);
            
    }
  
/**
* @Route("/page/{id}", name="frontoffice.show")
@param Request $request
* @param $id
* @return \Symfony\Component\HttpFoundation\Response
*/
// la ppage unique

public function show1($id, Request $request)
{
   $repo = $this->getDoctrine()->getRepository(Page::class);
   $page = $repo->find($id);
  
   return $this->render('frontoffice/page.html.twig', [
    'controller_name' => 'FrontofficeController',
       'page'            => $page
        ]);
    }
}   