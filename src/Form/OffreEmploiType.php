<?php

namespace App\Form;

use App\Entity\OffreEmploi;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                    'class' => categorie::class,
                    'choice_label' => 'nom',
                    'query_builder' => function (EntityRepository $repo) {
                        return $repo->createQueryBuilder('c');
                    }
                ]
            )
            ->add('maxSalary')
            ->add('minSalary')
            ->add('location')
            ->add('file', FileType::class, array('data_class' => null))
            ->add('email')
            ->add('Upload', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreEmploi::class,
        ]);
    }
}
