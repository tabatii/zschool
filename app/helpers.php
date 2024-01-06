<?php

use Filament\Facades\Filament;

if (! function_exists('panel')) {
    function panel(?string $id = null) {
        return filled($id) ? Filament::getPanel($id) : Filament::getCurrentPanel();
    }
}

if (! function_exists('tenant')) {
    function tenant() {
        return Filament::getTenant();
    }
}

if (! function_exists('filament_route')) {
    function filament_route(string $route, array $params = []) {
        $route = explode('@', ltrim($route, '\\'), 2);
        return ('\\App\\Filament\\' . $route[0])::getUrl($route[1], $params);
    }
}
