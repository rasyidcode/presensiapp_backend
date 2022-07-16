<?php

function isMenuOpen(string $module, int $segment = 0) {
    $segments = service('uri')->getSegments();
    try {
        return $segments[$segment] == $module ? 'menu-open' : '';
    } catch (Exception $e) {
        return '';
    }
}

function isLinkActive(string $module, int $segment = 0) {
    $segments = service('uri')->getSegments();
    try {
        return $segments[$segment] == $module ? 'active' : '';
    } catch (Exception $e) {
        return '';
    }
}

function isLinkActiveColor(string $module, int $segment = 0, string $color = '') {
    $segments = service('uri')->getSegments();
    try {
        return $segments[$segment] == $module ? 'white' : $color;
    } catch (Exception $e) {
        return $color;
    }
}