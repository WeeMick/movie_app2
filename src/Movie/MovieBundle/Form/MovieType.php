<?php

namespace Movie\MovieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('director', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('summary', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('actors', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('running_time', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('image_file', FileType::class, array(
                'mapped' => false,
                'label' => 'Upload image file for movie',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG or Jpeg file',
                    ])
                ],
                'attr' =>
                    array('class' => 'form-control'
                    )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-2')))
        ;
    }
}