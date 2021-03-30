<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CompanyprofileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('companyName', TextType::class)
            ->add('contactEmail', TextType::class)
            ->add('website', TextType::class)
            ->add('foundedDate', DateType::class)
            ->add('category', TextType::class)
            ->add('country', TextType::class)
            ->add('description', TextType::class)
            ->add('contactPhone', NumberType::class)
            ->add('companyImageName', FileType::class, ['attr' => ['class' => 'custom-file-input'],
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid ImageFile',
                    ])
                ],
            ])
            ->add('companyAdress', TextType::class, ['attr' => ['id' => 'searchTextField','autocomplete'=>'on']
            ])
            ->add('facebookLink', UrlType::class)
            ->add('twitterLink', UrlType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
