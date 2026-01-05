<?php

declare(strict_types=1);

// namespace HomeController;

function index(): void {
    dd('Home index page');
    layout("resources/views/pages/home.php", ["okay" => 'okay cool this is okay']);
}

function store(): void {
    dd('Home store page');
    layout("resources/views/pages/home.php", ["okay" => 'okay cool this is okay']);
}