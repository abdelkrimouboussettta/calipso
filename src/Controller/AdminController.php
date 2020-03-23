<?php

namespace App\Controller;
use App\Entity\Page;
use App\Entity\Categorie;



use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/page", name="admin.page")
     */
    public function page(Request $request )
    {
        $repo=$this->getDoctrine() ->getRepository(Page::class);
        $pages=$repo->findAll();
            
             
        return $this->render('admin/page.html.twig', [
            'controller_name' => 'AdminController',
        'pages'=>$pages
            ]);
    }
    /**
     * @Route("/admin/form/page", name="admin.form.page")
     */
    public function pageForm (Request $request, ObjectManager $manager)
    {
        $page =new Page();
        $form = $this->createFormBuilder($page)
        ->add('titre')
        ->add('auteur')
        ->add('createdAt')
        ->add('jourAt')
        ->add('contenu')
        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            "choice_label" => 'titre'
      ])
         
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($page); 
        $manager->flush();
        return $this->redirectToRoute('admin.page', 
        ['id'=>$page->getId()]); // Redirection vers la page
        }
        return $this->render('admin/pageform.html.twig', [
            'formPage' => $form->createView()
        ]);
    }


    /**
    * @Route("/admin/page/{id}", name="admin.page.modif")
    */
    
    public function pageModif(page $page, Request $request, ObjectManager $manager)
    {
        $form = $this->createFormBuilder($page)
        ->add('titre')
        ->add('auteur')
        ->add('createdAt')
        ->add('jourAt')
        ->add('contenu')
        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            "choice_label" => 'titre'
    ])
         
        ->getForm();
        $form->handleRequest($request);
                
        if($form->isSubMitted() && $form->isValid()){
            $manager->persist($page);
            $manager->flush();

            return $this->redirectToRoute('admin.page', 
            ['id'=>$page->getId()]); // Redirection vers la page
        }
        return $this->render('admin/pagemodif.html.twig', [
               'formModifPage' => $form->createView()
               ]);
    }
    /**
    * @Route("/admin/page/{id}/deletart", name="admin.page.sup")
    */
    
    public function pageSup($id, ObjectManager $manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Page::class);
        $page = $repo->find($id);

        $manager->remove($page);
        $manager->flush();
        
        return $this->redirectToRoute('admin.page');
    }
    /**
     * @Route("/admin/categorie", name="admin.categorie")
     */
    public function categorie(Request $request)
    {
        $repos=$this->getDoctrine() ->getRepository(Categorie::class);
        $categories=        $repos->findAll();
            
        return $this->render('admin/categorie.html.twig', [
            'controller_name' => 'AdminController',
        'categories'=>$categories
            ]);
    }

    /**
     * @Route("/admin/form/categorie", name="admin.form.categorie")
     */
    public function categorieForm(Request $request, ObjectManager $manager)
    {
        $categorie = new Categorie();
        $form = $this->createFormBuilder($categorie)
        ->add('titre')
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($categorie); 
        $manager->flush();
        return $this->redirectToRoute('admin.categorie', 
        ['id'=>$categorie->getId()]); // Redirection vers
        }
        return $this->render('admin/catform.html.twig', [
            'formCategorie' => $form->createView()
        ]);
    }
    /**
    * @Route("/admin/categorie/{id}", name="admin.categorie.modif")
    */
    
    public function modifCategorie(categorie $categorie, Request $request, ObjectManager $manager)
    {
        $form = $this->createFormBuilder($categorie)
        ->add('titre')
        ->getForm();
        $form->handleRequest($request);
                
        if($form->isSubMitted() && $form->isValid()){
        $manager->persist($categorie);
        $manager->flush();

        return $this->redirectToRoute('admin.categorie', 
        ['id'=>$categorie->getId()]);
        }
        return $this->render('admin/catmodif.html.twig', [
        'formModifCat' => $form->createView()
        ]);
    }
    /**
    * @Route("/admin/categorie/{id}/deletcat", name="admin.categorie.sup")
    */
    
    public function supCategorie($id, ObjectManager $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repo->find($id);

        $Manager->remove($categorie);
        $Manager->flush();
        
        return $this->redirectToRoute('admin.categorie');
    }
}
    
 
