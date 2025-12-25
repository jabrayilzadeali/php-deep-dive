<?php

declare(strict_types=1);

$ROUTES = [
    // [
    //     'uri' => ['/', '/home'],
    //     'method' => 'get',
    //     'controller' => 'home@index',
    //     'name' => 'home'
    // ],
    [
        'uri' => '/blogs',
        'method' => 'get',
        'controller' => 'blog@index',
        'name' => 'blogs'
    ],
    [
        'uri' => '/tesdt',
        'method' => 'get',
        'controller' => 'blog@index',
        'name' => 'blogs'
    ],
    [
        'uri' => '/blogs/{blog}',
        'method' => 'get',
        'controller' => 'blog@show',
        'name' => 'blog'
    ],
    [
        'uri' => '/blogs/{blog}/comments',
        'method' => 'get',
        'controller' => 'comment@index',
        'name' => 'comments'
    ],
    [
        'uri' => '/blogs/{blog}/comments/{comment}',
        'method' => 'get',
        'controller' => 'comment@show',
        'name' => 'comments'
    ],
];
function static_dynamic_url_portion(string $x): array
{
    return [
        'value' => $x,
        'is_dynamic' => str_starts_with($x, '{') && str_ends_with($x, '}')
    ];
}
// dd($_SERVER);
// $currentUri = $_SERVER['REQUEST_URI'];
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentUri = rtrim($currentUri, '/') ?: '/';
$currentUriPortions = explode('/', ltrim($currentUri, '/'));

foreach ($ROUTES as $route) {
    $uri = $route['uri'];
    $uriPortions = array_map('static_dynamic_url_portion', explode('/', ltrim($uri, '/')));
    echo '<pre>';
    var_export($uriPortions);
    echo '</pre>';
    if (is_array($uri)) {
        if (in_array($currentUri, $uri)) {
            dd($currentUri);
        }
    } else if (is_string($uri)) {
        $uriPortions = explode('/', $uri);
        // dd($uri);
        foreach ($uriPortions as $index => $portion) {
            if ($portion['value'] === $currentUriPortions[$index]) {}
        }
        // dd($_SERVER['REQUEST_URI'], $uri, $uriPortions);
        // if ($currentUri === $uri) {
        //     dd($currentUri, $uri, );
        // }
    }
}
// if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/home') {
//     layout("resources/views/pages/home.php", ["okay" => $okay]);
// } else if ($_SERVER['REQUEST_URI'] === '/blogs') {
//     layout("resources/views/pages/blogs.php", ["okay" => $okay]);
// } else if (str_starts_with($_SERVER['REQUEST_URI'], '/blogs')) {
//     $chunks = explode('/', $_SERVER['REQUEST_URI']);
//     dd($_SERVER, $chunks);
// }