<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

// use Symfony\Component\Validator\Constraints\NotBlank;

class AccountType extends AbstractType
{
    protected $auth;

    public function __construct(AuthorizationCheckerInterface $auth)
    {
        $this->auth = $auth;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($this->auth->isGranted('ROLE_MANAGER')) {
            $builder
                ->add('balance', IntegerType::class, [
                    'label' => false,
                    'required' => false
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Conta Corrente' => 1,
                        'Conta Poupança' => 2,
                        'Conta Salário' => 3,
                    ],
                    'label' => false,
                    'required' => false
                ])
                ->add('agency', null, [
                    'label' => false,
                    'required' => false
                ])
                ->add('clients', null, [
                    'by_reference' => false,
                    'label' => false,
                    'required' => false
                ])
                ->add('status')
                // ->add('isActive', null, [
                //     'attr' => [
                //         'class' => '',
                //         'style' => 'position:relative;bottom:100px'
                //     ],
                //     'label_attr' => [
                //         'class' => 'fw-bold me-2',
                //         'style' => 'position:relative;bottom:100px'
                //     ],
                //     'label' => 'Tornar esta conta ativa'
                // ])
            ;
        } else {
            $builder
                ->add('balance', IntegerType::class, [
                    'label' => false,
                    'required' => false
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Conta Corrente' => 1,
                        'Conta Poupança' => 2,
                        'Conta Salário' => 3,
                    ],
                ])
                ->add('agency', null, [
                    'label' => false,
                    'required' => false
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'allow_extra_fields' => true
        ]);
    }
}
