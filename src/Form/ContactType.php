<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'    => 'Firstname',
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label'    => 'Lastname',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label'    => 'Email',
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'label'    => 'Message',
                'required' => true,
            ])
            // ->add('createdAt', DateType::class, [
            //     'label'    => '',
            //     'required' => true,
            // ])
            // ->add('status', CheckboxType::class, [
            //     'label'    => '',
            //     'required' => true,
            // ])
            ->add('save', SubmitType::class, [
                'label'    => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
