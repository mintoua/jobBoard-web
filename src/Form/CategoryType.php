<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', null, array(
                'label' => 'Catégorie', 'attr' => ['placeholder' => 'nouvelle catégorie', 'label' => 'Categorie', 'style' => 'width: 580px']
            ))
            ->add(
                'descriptionc',
                TextareaType::class,
                array(
                    'label' => 'Description', 'attr' => ['placeholder' => 'description', 'label' => 'Description', 'style' => 'width: 580px']
                )
            )
            ->add('couleur', colorType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
