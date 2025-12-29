<?php

declare(strict_types=1);

$ROUTES = [
    [
        'id' => 0,
        'uri' => '/',
        'method' => 'GET',
        'controller' => 'home@index',
        'name' => 'home',
    ],
    [
        'id' => 0,
        'uri' => '/home',
        'method' => 'GET',
        'controller' => 'home@index',
        'name' => 'home',
    ],
    [
        'id' => 0,
        'uri' => '/home/{home}',
        'method' => 'GET',
        'controller' => 'home@index',
        'name' => 'home',
    ],
    [
        'id' => 0,
        'uri' => '/home/{cool}/okay',
        'method' => 'GET',
        'controller' => 'home@index',
        'name' => 'home',
    ],
    [
        'id' => 1,
        'uri' => '/blogs',
        'method' => 'GET',
        'controller' => 'blog@index',
        'name' => 'blogs',
    ],
    [
        'id' => 3,
        'uri' => '/blogs/create',
        'method' => 'GET',
        'controller' => 'blog@show',
        'name' => 'blog',
    ],
    [
        'id' => 4,
        'uri' => '/blogs/{blog}',
        'method' => 'GET',
        'controller' => 'blog@show',
        'name' => 'blog',
    ],
    [
        'id' => 5,
        'uri' => '/blogs/{blog}/comments',
        'method' => 'GET',
        'controller' => 'comment@index',
        'name' => 'comments',
    ],
    [
        'id' => 6,
        'uri' => '/blogs/{blog}/zor/okay',
        'method' => 'GET',
        'controller' => 'comment@show',
        'name' => 'comments',
    ],
    [
        'id' => 7,
        'uri' => '/blogs/{blog}/zor/{power}',
        'method' => 'GET',
        'controller' => 'comment@show',
        'name' => 'comments',
    ],
    [
        'id' => 8,
        'uri' => '/blogs/{blog}/power/{power}',
        'method' => 'GET',
        'controller' => 'comment@show',
        'name' => 'comments',
    ],
    [
        'id' => 9,
        'uri' => '/blogs/{blog}/comments/{comment}',
        'method' => 'GET',
        'controller' => 'comment@show',
        'name' => 'comments',
    ],
];

$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentUri = rtrim($currentUri, '/') ?: '/';
$currentUriPortions = explode('/', ltrim($currentUri, '/'));
$currentUriPortionsLength = count($currentUriPortions);
$FILTERED_ROUTES = array_filter($ROUTES, fn($route) => count(explode('/', ltrim($route['uri'], '/'))) === $currentUriPortionsLength);

$correctUri = null;
$isFoundCorrectUrl = true;


function removeBrackets(string $x): string
{
    return substr($x, 1, -1);
}
function static_dynamic_url_portion(string $x): array
{
    return [
        'value' => $x,
        'is_dynamic' => str_starts_with($x, '{') && str_ends_with($x, '}')
    ];
}


function correctUrl($uriPortions, $currentUriPortions, $route): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
        return false;
    }
    foreach ($uriPortions as $index => $portion) {
        // echo $portion['value'] . ' => ' . $currentUriPortions[$index] . ' | ';
        if ($portion['is_dynamic']) {
            // echo $currentUriPortions[$index] . ' | ';
            continue;
        }
        if ($portion['value'] !== $currentUriPortions[$index]) {
            // $isFoundCorrectUrl = false;
            return false;
            // break;
        }
        // $isFoundCorrectUrl = true;
    }

    $correctUri = $route;
    // $dynamicUriPortions = array_filter($uriPortions, fn($uriPortion) => $uriPortion['is_dynamic']);
    foreach ($uriPortions as $index => $uriPortion) {
        if (!$uriPortion['is_dynamic']) continue;

        $paramName = removeBrackets($uriPortion['value']);
        $paramValue = $currentUriPortions[$index];

        $correctUri['dynamicValues'][$paramName] = $paramValue;
    }

    return true;
}

// $currentUri = $_SERVER['REQUEST_URI'];

foreach ($FILTERED_ROUTES as $route) {
    $uri = $route['uri'];
    $uriPortions = array_map('static_dynamic_url_portion', explode('/', ltrim($uri, '/')));
    if (correctUrl($uriPortions, $currentUriPortions, $route)) dd($currentUri);
}

dd('404 not found');
