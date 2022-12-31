# atividadePhp
# Developed by Philip and Hélio


# For login and registration purposes
## Documentation: https://symfony.com/doc/current/security.html#form-login


## MIGRATIONS COMANDS:
# php bin/console make:migration
# php bin/console doctrine:migrations:migrate


## Doc para saber se é admin ou user na hora de logar: is_granted('ROLE_ADMIN')
# https://symfonycasts.com/screencast/symfony2-ep2/twig-security-is-authenticated


## PERSMISSIONS ADDED
- ('ROLE_ADMIN') PODE VER, CRIAR, EDITAR TODOS OS ITEMS
- ('ROLE_USER') PODE VER, CRIAR, EDITAR APENAS TRANSAÇOES
# TO DO:
- ('ROLE_MANAGER') PODE VER, CRIAR, EDITAR ITEMS ESPECÍFICOS