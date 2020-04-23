<?php

namespace App\Form;

use App\Entity\Page;
use App\Entity\Fichier;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('auteur')
            ->add('contenu', CKEditorType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                "choice_label" => 'titre'
          ])
          ->add('fichier', FileType::class, [
            // 'mapped'   => false, //@todo A enlever, je l'ai mis pour qu'il ne le prenne pas en compte lors de l'insertion en BDD
                    ])
                    ->add('parent', EntityType::class, [
                    'class' => Page::class,
                        "choice_label" => 'titre'
                      ]);
            
                }
                    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
