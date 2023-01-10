# atividadePhp
# Developed by Philip and Hélio

## EXECUTANDO  O PROJETO
- No terminal, execute "composer install-reqs".

# ROLE_HIERARCHY
- ROLE_MANAGER:  ROLE_USER
- ROLE_ADMIN: [ROLE_MANAGER, ROLE_ALLOWED_TO_SWITCH]


# como adicionar estilo no formtype:

->add('isActive', null, [
    'attr' => [
        'class' => '',
        'style' => 'position:relative;bottom:100px'
    ],
    'label_attr' => [
        'class' => 'fw-bold me-2',
        'style' => 'position:relative;bottom:100px'
    ],
    'label' => 'Tornar esta conta ativa'
]);

