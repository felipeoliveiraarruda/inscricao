## Provider para utilização de senha única USP no Laravel

Biblioteca que permite integrar sua aplicação laravel com a autenticação centralizada da USP utilizando a senha única.

Como funcionalidades adicionais, além da comunicação com o servidor de autenticação, ele também fornece:

- as rotas e controllers necessários para efetuar o login e logout da aplicação;
- um sistema mínimo de autorização em três níveis (permission) para a aplicação;
- uma rota `/loginas` quer permite assumir identidade de outra pessoa.

> OBS.: Os recursos adicionais podem ser desativados caso não deseje utilizar.

Vídeos sobre a utilização desta biblioteca:

- [1.x](https://youtu.be/jLFM2AUFJgw)
- [2.x](https://www.youtube.com/watch?v=t6Zf3nK-oIo)
- [3.x] ...
- [4.x] ...

Dependências em PHP, além das default do laravel:

    php-curl

### Instalação

    composer require uspdev/senhaunica-socialite

### Configuração nova

#### Publique e rode as migrations

As migrations modificam a tabela `users` e criam as tabelas de autorização.

    php artisan vendor:publish --provider="Uspdev\SenhaunicaSocialite\SenhaunicaServiceProvider" --tag="migrations"
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    php artisan migrate

#### Declarar a trait do model User

Em `App/Models/User.php`, dentro da classe `User` incluir as seguintes linhas:

    class User extends Authenticatable
    {
        use \Spatie\Permission\Traits\HasRoles;
        use \Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;
        ...
#### Cadastre o `callback_id`

A url é o que está cadastrado no `APP_URL` mais `/callback`, exemplo: `http://localhost:8000/callback`

- dev: https://dev.uspdigital.usp.br/adminws/oauthConsumidorAcessar
- prod: https://uspdigital.usp.br/adminws/oauthConsumidorAcessar

#### Coloque variáveis no .env e .env.example da sua aplicação

    # SENHAUNICA-SOCIALITE ######################################
    # https://github.com/uspdev/senhaunica-socialite
    SENHAUNICA_KEY=fflch_sti
    SENHAUNICA_SECRET=sua_super_chave_segura
    SENHAUNICA_CALLBACK_ID=85

    # URL do servidor oauth no ambiente de dev (default: no)
    #SENHAUNICA_DEV="https://dev.uspdigital.usp.br/wsusuario/oauth"

    # URL do servidor oauth para uso com senhaunica-faker
    #SENHAUNICA_DEV="http://127.0.0.1:3141/wsusuario/oauth"

    # Esses usuários terão privilégios especiais
    #SENHAUNICA_ADMINS=11111,22222,33333
    #SENHAUNICA_GERENTES=4444,5555,6666

    # Se os logins forem limitados a usuários cadastrados (onlyLocalUsers=true),
    # pode ser útil cadastrá-los aqui.
    #SENHAUNICA_USERS=777,888

    # Se true, os privilégios especiais serão revogados ao remover da lista (default: false)
    #SENHAUNICA_DROP_PERMISSIONS=true

    # Habilite para salvar o retorno em storage/app/debug/oauth/ (default: false)
    #SENHAUNICA_DEBUG=true

    # SENHAUNICA-SOCIALITE ######################################

### Atualizando à partir da versão 2

A atualização para versão 4 exije alguns ajustes no código.

Primeiramente atualize o `composer.json` para usar a nova versão e rode `composer update`.

    "uspdev/senhaunica-socialite": "^4.0"

Deve-se desfazer/verificar **pelo menos** os seguintes arquivos:

- `app/Providers/EventServiceProvider.php`, remover as linhas que chamam o SenhaunicaSocialite
- `config/services.php`, remover a seção senhaunica

Por padrão a versão 4 incorpora autorização e rotas/controller internos. Se for conveniente, esses recursos podem ser desabilitados por meio do `config/senhaunica.php`. Se optar por utilizar esses recursos, verifique/ajuste os seguintes arquivos:

- `routes/web.php`, remover as rotas login, callback e logout
- `App/Http/Controllers/Auth/LoginController.php`, apagar o arquivo
- `App/Providers/AuthServiceProvider.php`, remover gates `admin` e `user`

A tabela `users` deve possuir a coluna `codpes`. Se for o caso, publique a migration e ajuste o arquivo publicado conforme sua necessidade.

Para usar a autorização, é necessário:

- Incluir as traits no model do user

        use \Spatie\Permission\Traits\HasRoles;
        use \Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;

- publicar e migrar as tabelas correspondentes:

        php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
        php artisan migrate

Confira o `.env` e o `.env.example` se estão de acordo com as recomendações atuais.

> OBS.: a variável `ADMINS` foi renomeada para `SENHAUNICA_ADMINS`.

### Arquivo de configuração

Caso você queira modificar o comportamento padrão de algumas partes como por exemplo, desabilitar a autorização ou as rotas internas, publique o arquivo de configuração e ajuste conforme necessário. A publicação é necessária somente se for alterar alguma configuração.

    php artisan vendor:publish --provider="Uspdev\SenhaunicaSocialite\SenhaunicaServiceProvider" --tag="config"

- **permission** (padrão true)

  As permissões internas de três níveis serão utilizadas por meio da biblioteca `spatie-permissions`. Com isso, os gates `admin`, `gerentes` e `user` estarão disponíveis.

- **onlyLocalUsers** (padrão false)

  Por padrão, qualquer usuário com senha única poderá fazer login. Mudando para true, a biblioteca permitirá somente o login de pessoas que já estão na base de dados local ou que estejam na lista de codpes do `.env`.

- **destroyUser** (padrão false)

  Se true a interface interna de gerenciamento permitirá a remoção de usuários da base local. Use com cuidado caso outras tabelas dependa/tenham relacionamento com `users`.

#### Rotas e controllers

Essa biblioteca possui rotas internas para **login**, **logout**, **users** e **loginas** e o respectivo controller fornecendo uma solução pronta para muitas aplicações.

Caso sua aplicação necessite de um processo mais complexo, você pode desabilitar com `routes=false`. Nesse caso, não é necessário usar a migration que modifica a tabela users.

Mas você deve implementar sua solução de rotas e controller para gerenciar os logins e logouts usando senha única ou não.

#### Menu na aplicação

No `config/laravel-usp-theme.php`, coloque ou reposicione a chave `senhaunica-socialite` para mostrar o menu. Ele será visível somente para `admin`.

    [
        'key' => 'senhaunica-socialite',
    ],

#### Autorização

Se você desabilitar as permissões `permission=false` não é necessário usar a migration do `spatie/laravel-permission`.

## Gerenciamento de Usuários

A biblioteca possui um painel de gerenciamento de usuários. A rota padrão é `/users` mas pode ser modificado nas configurações.

Essa interface permite adicionar e remover usuários, ajustar as permissões (admin, gerente e usuário), dentre outras facilidades. Ela é autorizada somente para usuários `admin`.

A partir da **versão 4.2**, é possivel adicionar uma coluna personalizada. Veja a documentação sobre [customUserField](docs/customUserField.md).

A partir da **versão 4.3** está disponível componente select para procurar pessoas. Veja documentação sobre [componentes](docs/componentes.md).

## Configuração da biblioteca laravel-permission

A biblioteca [laravel-permission](https://github.com/spatie/laravel-permission/) vem habilitada por padrão. Ela é poderosa, flexível e bem estabelecida pela comunidade laravel no quesito grupos e permissões.

Os números USP inseridos em SENHAUNICA_ADMINS e SENHAUNICA_GERENTES recebem as permissões **admin** e **gerente** respectivamente. Todos os usuários por padrão recebem a permissão **user**. Essas permissões são automaticamente `Gates`, assim não é necessário definí-las em \_AuthServiceProvider*.

OBS.: Os **admins** são SUPER-ADMINS, ou seja eles possuem acesso em todos os gates.

Neste momento você tem um poder enorme de regras de permissionamento no seu sistema, podendo criar outras _permissions_, agrupá-las em _roles_ ou mesmo listar as permissões de um usuários, como:

    $user->getPermissionNames();

Ou listar todos usuários com uma dada permissão:

    $users = User::permission('admin')->get();

Como as permissões são gates, eles podem ser usados diretamente no blade com a diretiva `@can` ou em qualquer parte do sistema da forma usual.

### Gerenciamento das Permissões dos Usuários

Por padrão, a rota /users estará disponível para os admins. Nela é possível gerenciar as permissões dos usuários, incluindo gerentes e admins, desde que não estejam no `.env`.

## Informações para desenvolvedores(as):

### Senhaunica-faker

Em ambiente de desenvolvimento, ao invés de usar a autenticação por senha única, é possivel utilizar a biblioteca [senhaunica-faker](https://github.com/uspdev/senhaunica-faker). Essa biblioteca simula o servidor de autenticação retornando dados fake para a aplicação.

### Direto na aplicação

Caso deseje ver todos parâmetros retornados na requisição, em Server.php:

```php
public function userDetails($data, TokenCredentials $tokenCredentials)
{
    dd($data);
}
```

### Debug

Outra possibilidade é configurar a variável `SENHAUNICA_DEBUG` como `true`. Isso salvará em JSON as informações obtidas de `<Servidor de OAuth1>/wsusuario/oauth/usuariousp` no diretório `storage/app/debug/oauth` por número USP.

Ex: para o número USP 3141592, os dados serão salvos em `storage/app/debug/oauth/3141592.json`.
