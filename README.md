# atividadePhp
# Developed by Philip and Hélio


# For login and registration purposes
## Documentation: https://symfony.com/doc/current/security.html#form-login


## MIGRATIONS COMANDS:
# php bin/console make:migration
# php bin/console doctrine:migrations:migrate


## Doc para saber se é admin ou user na hora de logar: is_granted('ROLE_ADMIN')
# https://symfonycasts.com/screencast/symfony2-ep2/twig-security-is-authenticated
# https://symfony.com/doc/current/security.html


## PERSMISSIONS ADDED
- ('ROLE_ADMIN') PODE VER, CRIAR, EDITAR TODOS OS ITEMS
- ('ROLE_USER') PODE VER, CRIAR, EDITAR APENAS TRANSAÇOES
# TO DO:
- ('ROLE_MANAGER') PODE VER, CRIAR, EDITAR ITEMS ESPECÍFICOS


## ADDING PERMS TO A USER IN DATABASE
ROLES = ["ROLE_EXAMPLE"] WHERE EXAMPLE IS ADMIN OR USER

# ----------------------------------------------------- #
# role_hierarchy:
- ROLE_ADMIN:       ROLE_USER
- ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

* Users with the ROLE_ADMIN role will also have the ROLE_USER role. 
* Users with ROLE_SUPER_ADMIN, will automatically have ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH and ROLE_USER (inherited from ROLE_ADMIN).
* https://symfony.com/doc/current/security.html


## EXECUTANDO  O PROJETO
- No terminal, execute "composer install-reqs".