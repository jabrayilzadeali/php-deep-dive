<?php

declare(strict_types=1);

// namespace BlogController;

function index(): void {
    dd('Blog index page');
}

function show($cool): void {
    dd('Blog show page', $cool);
}