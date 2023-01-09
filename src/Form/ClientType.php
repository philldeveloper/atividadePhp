<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('phone', NumberType::class, [
                'label' => false,
                'required' => false,
                ])
            ->add('active', CheckboxType::class, [
                'label' => false,
                'required' => false
            ])
            // ->add('accounts', null, [
            //     'by_reference' => false,
            //     'required' => false,
            // ])
            // ->add('user', null, [
            //     'label' => false,
            //     'required' => false,
            //        'mapped' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'allow_extra_fields' => true
        ]);
    }
}
