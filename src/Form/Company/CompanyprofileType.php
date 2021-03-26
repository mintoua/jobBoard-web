<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyprofileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyname', TextType::class)
            ->add('contactemail', TextType::class)
            ->add('website', TextType::class)
            ->add('foundeddate', DateType::class)
            ->add('category', TextType::class)
            ->add('country', TextType::class)
            ->add('description', TextType::class)
            ->add('contactphone', NumberType::class)
            ->add('companyadress', UrlType::class)
            ->add('facebooklink', UrlType::class)
            ->add('twitterlink', UrlType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
