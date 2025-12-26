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
        'name' => 'blogs',
        'numberOfPortions' => 1,
    ],
    [
        'uri' => '/tesdt',
        'method' => 'get',
        'controller' => 'blog@index',
        'name' => 'blogs',
        'numberOfPortions' => 1,
    ],
    [
        'uri' => '/blogs/create',
        'method' => 'get',
        'controller' => 'blog@show',
        'name' => 'blog',
        'numberOfPortions' => 2,
    ],
    [
        'uri' => '/blogs/{blog}',
        'method' => 'get',
        'controller' => 'blog@show',
        'name' => 'blog',
        'numberOfPortions' => 2,
    ],
    [
        'uri' => '/blogs/{blog}/comments',
        'method' => 'get',
        'controller' => 'comment@index',
        'name' => 'comments',
        'numberOfPortions' => 3,
    ],
    [
        'uri' => '/blogs/{blog}/zor/okay',
        'method' => 'get',
        'controller' => 'comment@show',
        'name' => 'comments',
        'numberOfPortions' => 4,
    ],
    [
        'uri' => '/blogs/{blog}/zor/{power}',
        'method' => 'get',
        'controller' => 'comment@show',
        'name' => 'comments',
        'numberOfPortions' => 4,
    ],
    [
        'uri' => '/blogs/{blog}/power/{power}',
        'method' => 'get',
        'controller' => 'comment@show',
        'name' => 'comments',
        'numberOfPortions' => 4,
    ],
    [
        'uri' => '/blogs/{blog}/comments/{comment}',
        'method' => 'get',
        'controller' => 'comment@show',
        'name' => 'comments',
        'numberOfPortions' => 4,
    ],
];
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

// dd($_SERVER);
// $currentUri = $_SERVER['REQUEST_URI'];

$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentUri = rtrim($currentUri, '/') ?: '/';
$currentUriPortions = explode('/', ltrim($currentUri, '/'));
$currentUriPortionsLength = count($currentUriPortions);
$FILTERED_ROUTES = array_filter($ROUTES, fn($route) => $route['numberOfPortions'] === $currentUriPortionsLength);

// dd($FILTERED_ROUTES);
$correctUri = null;
foreach ($FILTERED_ROUTES as $route) {
    $uri = $route['uri'];
    $uriPortions = array_map('static_dynamic_url_portion', explode('/', ltrim($uri, '/')));

    if (is_array($uri)) {
        if (in_array($currentUri, $uri)) {
            dd($currentUri);
        }
    } else if (is_string($uri)) {
        // echo '<br>';
        $isFoundCorrectUrl = true;
        foreach ($uriPortions as $index => $portion) {
            // echo $portion['value'] . ' => ' . $currentUriPortions[$index] . ' | ';
            if ($portion['is_dynamic']) {
                // echo $currentUriPortions[$index] . ' | ';
                continue;
            }
            if ($portion['value'] !== $currentUriPortions[$index]) {
                $isFoundCorrectUrl = false;
                break;
            }
            // $isFoundCorrectUrl = true;
        }

        if ($isFoundCorrectUrl) {
            $correctUri = $route;
            // $dynamicUriPortions = array_filter($uriPortions, fn($uriPortion) => $uriPortion['is_dynamic']);
            foreach ($uriPortions as $index => $uriPortion) {
                if ($uriPortion['is_dynamic']) $correctUri['dynamicValues'][removeBrackets($uriPortion['value'])] = $currentUriPortions[$index];
            }
            

            dd($correctUri);
            break;
        }
    }
}

if (!$isFoundCorrectUrl) {
    dd('404 not found');
}
