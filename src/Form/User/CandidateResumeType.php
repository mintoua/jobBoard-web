<?php

namespace App\Form;

use App\Entity\CandidateResume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateResumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ResumeHeadline', TextareaType::class)
            ->add('Skills', TextType::class)
            ->add('Experience', TextType::class)
            ->add('Education', CollectionType::class, [
                'entry_type' => EducationType::class,
                'entry_options' => [
                    'label' => true
                ],
                'by_reference' => true,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])



            ->add('Certification', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'  ]
            ])
          /**  ->add('userId', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])*/
        ;
    }

        public
        function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => CandidateResume::class,
            ]);
        }
    }
