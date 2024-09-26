<h1 align="center">
<br>💋 Rede Sugar 
</h1>

## Tecnologias utilizadas

- [Laravel 10](https://laravel.com/)
- [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
- [Laravel Fortify](https://laravel.com/docs/10.x/fortify)
- [Laravel Sail](https://laravel.com/docs/10.x/sail)
- [Mailpit](https://github.com/axllent/mailpit)
- [Scribe](https://scribe.knuckles.wtf/)

## Versões
- PHP 8.2
- MySQL 8

## Docker
Execute os seguintes comandos

### Cria a rede externa do projeto
`docker network create redesugar`

### Pela primeira vez ao clonar o projeto, execute o comando abaixo
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

### Todas as outras vezes que quiser subir o projeto, execute o comando abaixo
`./vendor/bin/sail up -d`


### Para facilitar, pode adicionar o alias abaixo no ~/.bashrc
`alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`

Para aplicar, rode um `source ~/.bashrc` após salvar o arquivo, agora será possível rodar apenas `sail up -d` no projeto

### Gerar token de sistema para backoffice:
1 - Acessar o Tinker: `sail artisan tinker`

2 - Buscar usuário de sistema: `$user = User::find(1);`

3 - Gerar token com escopo: `$user->createToken('backoffice-api-token', ['backoffice'])->plainTextToken;`

4 - Colar token de saída no `app/config/application.ini` do projeto do backoffice
