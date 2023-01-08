<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransactionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('value', IntegerType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'required' => false,
                'help' => 'Informe uma pequena descrição da transação',
            ])
            // ->add('account')
            // ->add('client')
            ->add('targetAccount')
            // ->add('targetAccount', null, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Por favor, selecione uma conta alvo.',
            //         ]),
            //     ],
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
            'allow_extra_fields' => true
        ]);
    }
}
