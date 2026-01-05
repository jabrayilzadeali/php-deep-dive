<?php

declare(strict_types=1);


require_once BASE_PATH . "/app/Http/Controllers/blogController.php";

$ROUTES = [
    [
        'id' => 0,
        'uri' => '/',
        'method' => 'GET',
        'controller' => 'HomeController@index',
        'name' => 'home',
    ],
    [
        'id' => 0,
        'uri' => '/',
        'method' => 'POST',
        'controller' => 'HomeController@store',
        'name' => 'home',
    ],
    [
        'id' => 1,
        'uri' => '/blogs',
        'method' => 'GET',
        'controller' => fn () => BlogController\index(),
        'name' => 'blogs',
    ],
    [
        'id' => 3,
        'uri' => '/blogs/create',
        'method' => 'GET',
        'controller' => 'BlogController@show',
        'name' => 'blog',
    ],
    [
        'id' => 4,
        'uri' => '/blogs/{blog}',
        'method' => 'GET',
        'controller' => 'BlogController@show',
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


function getCurrentUrlObj($uriPortions, $currentUriPortions, $route)
{
    if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
        return null;
    }
    foreach ($uriPortions as $index => $portion) {
        // echo $portion['value'] . ' => ' . $currentUriPortions[$index] . ' | ';
        if ($portion['is_dynamic']) {
            // echo $currentUriPortions[$index] . ' | ';
            continue;
        }
        if ($portion['value'] !== $currentUriPortions[$index]) {
            // $isFoundCorrectUrl = false;
            return null;
            // break;
        }
        // $isFoundCorrectUrl = true;
    }

    // $dynamicUriPortions = array_filter($uriPortions, fn($uriPortion) => $uriPortion['is_dynamic']);
    foreach ($uriPortions as $index => $uriPortion) {
        if (!$uriPortion['is_dynamic']) continue;

        $paramName = removeBrackets($uriPortion['value']);
        $paramValue = $currentUriPortions[$index];

        $route['dynamicValues'][$paramName] = $paramValue;
    }

    return $route;
}

// $currentUri = $_SERVER['REQUEST_URI'];

foreach ($FILTERED_ROUTES as $route) {
    $uri = $route['uri'];
    $uriPortions = array_map('static_dynamic_url_portion', explode('/', ltrim($uri, '/')));
    $correctUri = getCurrentUrlObj($uriPortions, $currentUriPortions, $route);
    if ($correctUri) {
        if (is_callable($route['controller']))  {
            $route['controller']();
            // dd('callable bro cool');
        }
        [$file, $func] = explode('@', $route['controller']);
        // require_once BASE_PATH . "/app/Http/Controllers/BlogController.php";
        // extract($route['dynamicValues']);
        // dd($route, $correctUri);
        require_once BASE_PATH . "/app/Http/Controllers/$file.php";
        // $c = "$file\\$func";
        // $c();
        //
        // dd($correctUri, $correctUri['dynamicValues'], extract($correctUri['dynamicValues']));
        // dd(...$correctUri['dynamicValues']);
        $params = $correctUri['dynamicValues'] ?? [];
        // dd($params);
        $func(...$params);
        // dd($file, $func);
    };
}

dd('404 not found');
