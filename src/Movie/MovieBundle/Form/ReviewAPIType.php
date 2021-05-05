<?php

namespace Movie\MovieBundle\Form;

use Movie\MovieBundle\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAPIType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Movie\MovieBundle\Entity\Review',
            'csrf_protection' => false
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reviewer', TextType::class, array('attr' =>
                array('class' => 'form-control')))
//            ->add('movie', TextType::class, array('attr' =>
//                array('class' => 'form-control',
//                    'mapped' => false)))
            ->add('review', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('rating', TextType::class, array('attr' =>
                array('class' => 'form-control')));

    }



}