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
        if($this->auth->isGranted('ROLE_ADMIN')){
            $builder
                // ->add('number', IntegerType::class, [
                //     'label' => false,
                //     'required' => false,
                //     // 'constraints' => new NotBlank(),
                // ])
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
                ->add('users', null, [
                    'label' => false,
                    'required' => false
                ])
            ;

        }else {
            $builder
                // ->add('number', IntegerType::class, [
                //     'label' => false,
                //     'required' => false
                // ])
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
                ])
                // ->add('users', null, [
                //     'label' => false,
                //     'required' => false
                // ])
            ;
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
