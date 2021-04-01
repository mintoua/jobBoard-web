<?php

namespace App\Form;

use App\Entity\OffreEmploi;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OffreEmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('poste')
            ->add('description')
            ->add('date_expiration')
            ->add(
                'categorie',
                EntityType::class,
                [
                    'class' => Category::class
                ]
            )
            ->add('maxSalary')
            ->add('minSalary')
            ->add('location',TextType::class, ['attr' => ['id' => 'searchTextField','autocomplete'=>'on']
    ])
            ->add('file', FileType::class, array('data_class' => null))
            ->add('email',EmailType::class)
            ->add('Upload', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreEmploi::class,
        ]);
    }
}
