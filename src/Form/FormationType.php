<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['attr' => ['placeholder' => 'formation','style' => 'width: 380px']])
            ->add('formateur',null , array(
                'label' => 'Le formateur',
                'attr' => array('placeholder' => 'formateur','style' => 'width: 380px')
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array('placeholder' => 'une description pour la formation','style' => 'width: 380px')))
            ->add('date_debut')
            ->add('date_fin')
            ->add('adresse',null,['attr' => ['placeholder' => 'adresse','style' => 'width: 380px']])
            ->add('mail',null,['attr' => ['placeholder' => 'exemple@gmail.com','style' => 'width: 380px']])
            ->add('tel', null, ['attr' => ['placeholder' => 'numero telphone ********','style' => 'width: 380px']])
            ->add('prix',null,['attr' => ['placeholder' => 'prix','style' => 'width: 380px']])
            ->add('category',EntityType::class,['class' => Category::class,
                'choice_label' => 'titre',
                'label' => 'Catégorie'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
