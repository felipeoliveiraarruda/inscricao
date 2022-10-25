<?php

$admin = [
   /* [
        'text' => '<i class="fas fa-atom"></i> Edital',
        'url' => 'admin/edital',
    ],
    [
        'text' => '<i class="fas fa-atom"></i> Tipo de Documento',
        'url' => 'admin/tipo-documento',
    ],    
    /*[
        'text' => 'SubItem 2',
        'url' =>  '/subitem2',
        'can' => 'admin',
    ],
    [
        'type' => 'divider',
    ],
    [
        'type' => 'header',
        'text' => 'Cabeçalho',
    ],
    [
        'text' => 'SubItem 3',
        'url' => 'subitem3',
    ],*/
];

/*$submenu2 = [
    [
        'text' => 'SubItem 1',
        'url' => 'subitem1',
    ],
    [
        'text' => 'SubItem 2',
        'url' => 'subitem2',
        'can' => 'admin',
    ],
];*/

$menu = [
    [
        'text' => '<i class="fas fa-home"></i> Home',
        'url' => '/inscricao/public',
    ],
    [
        # este item de menu será substituido no momento da renderização
        'key' => 'menu_dinamico',
    ],
    /*[
        'text' => 'Drop Down',
        'submenu' => $submenu2,
        'can' => '',
    ],   */ 
    /*[
        'text' => 'Menu gerente',
        'url' => 'gerente',
        'can' => 'gerente',
    ],
    [
        'text' => 'Administração',
        'submenu' => $admin,
        'can' => 'admin', 'gerente',
    ],*/
    [
        'text' => '<i class="fas fa-user"></i> Usuário',
        'url' => 'admin/usuario',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-file-contract"></i> Edital',
        'url' => 'admin/edital',
        'can' => 'gerente',
    ],
    [
        'text' => '<i class="fas fa-file"></i> Tipo de Documento',
        'url' => 'admin/tipo-documento',
        'can' => 'admin',
    ],
   /* [
        'text' => '<i class="fas fa-file"></i> Endereço',
        'url' => 'endereco',
        'can' => '',
    ],    */
];

$right_menu = [
    [
        // menu utilizado para views da biblioteca senhaunica-socialite.
        'key' => 'senhaunica-socialite',
    ],
    /*[
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'target' => '_blank',
        'url' => config('app.url') . '/item1',
        'align' => 'right',
    ],*/
];


return [
    # valor default para a tag title, dentro da section title.
    # valor pode ser substituido pela aplicação.
    //'title' => config('app.name'),

    # USP_THEME_SKIN deve ser colocado no .env da aplicação 
    'skin' => env('USP_THEME_SKIN', 'uspdev'),

    # chave da sessão. Troque em caso de colisão com outra variável de sessão.
    'session_key' => 'laravel-usp-theme',

    # usado na tag base, permite usar caminhos relativos nos menus e demais elementos html
    # na versão 1 era dashboard_url
    'app_url' => config('app.url'),

    # login e logout
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'acesso',

    # menus
    'menu' => $menu,
    'right_menu' => $right_menu,
];
