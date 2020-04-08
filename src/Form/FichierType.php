<?php

namespace App\Form;

use App\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'typeContrat',
                ChoiceType::class,
                [
                    'choices' => array_flip(Fichier::TYPE),
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Choix...'
                ]
            )
            ->add('fichier', FileType::class, [
                'required' => true,
            ])
            ->add('saveReglesMetier', SubmitType::class)
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
        ]);
    }
}
