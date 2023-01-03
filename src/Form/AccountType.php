<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccountType extends AbstractType
{
    protected $auth;

    public function __construct(AuthorizationCheckerInterface $auth) {
        $this->auth = $auth;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('balance')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Conta Corrente' => 1,
                    'Conta Poupança' => 2,
                    'Conta Salário' => 3,
                ],
            ])
            ->add('agency')
            ->add('clients')
        ;
        
        // if($this->auth->isGranted('ROLE_ADMIN')) {
        //     $builder
        //         ->add('number')
        //         ->add('balance')
        //         ->add('type', ChoiceType::class, [
        //             'choices' => [
        //                 'Conta Corrente' => 1,
        //                 'Conta Poupança' => 2,
        //                 'Conta Salário' => 3,
        //             ],
        //         ])
        //         ->add('agency')
        //         ->add('clients')
        //     ;

        // }else if ($this->auth->isGranted('ROLE_USER')) {

        //     $builder
        //         ->add('number')
        //         ->add('balance')
        //         ->add('type', ChoiceType::class, [
        //             'choices' => [
        //                 'Conta Corrente' => 1,
        //                 'Conta Poupança' => 2,
        //                 'Conta Salário' => 3,
        //             ],
        //         ])
        //         ->add('agency')
        //     ;
        // }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
