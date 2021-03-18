<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
            ->add('q', TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search...'
                ]
            ])
            ->add('price', IntegerType::class,[
                'label' => false,
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Price'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver){

        $resolver->setDefaults([
            'data_class'=> SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}