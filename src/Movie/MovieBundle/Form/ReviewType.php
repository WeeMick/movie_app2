<?php

namespace Movie\MovieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('review', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('rating', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('reviewer', TextType::class, array('attr' =>
                array('class' => 'form-control',
                    'mapped' => false,
                    3 => 'Default value')))
            ->add('save', SubmitType::class, array(
                'label' => 'Save Review',
                'attr' => array('class' => 'btn btn-primary mt-2')));
    }
}