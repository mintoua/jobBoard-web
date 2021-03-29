<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=> [
                    'placeholder' =>'Event Name',
                    'class'=> 'form-control'
                ]
            ])
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder'=> 'dd/mm/yyyy',
                    'type'=>'date'
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=> [
                    'placeholder' =>'Event Description',
                    'class'=> 'form-control'
                ]
            ])
            ->add('prix',NumberType::class,[
                'attr'=> [
                    'placeholder' =>'Event Price',
                    'class'=> 'form-control'
                ]
            ])
            ->add('adresse',TextareaType::class,[
                'attr'=> [
                    'placeholder' =>'Event Adresss',
                    'class'=> 'form-control'
                ]
            ])
            ->add('image',FileType::class,[
                'attr'=> [
                    'placeholder' =>'Upload File',
                    'class'=> 'custom-file'
                ]
            ])
            ->add('nbrePlace',NumberType::class,[
                'attr'=> [
                    'placeholder' =>'Ticket Number',
                    'class'=> 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
