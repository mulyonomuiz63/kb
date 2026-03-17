<?php

function set_active($uri)
{
    $uris = service('uri');
    $uriSegment = strtolower($uris->getSegment(1).'/'.$uris->getSegment(2));
    if (is_array($uri)) {
        $no=0;
        foreach ($uri as $u) {
            if ($uriSegment == $uri["$no"]) {
                $data = array(
                    'menu'     => 'active',
                    'expanded' => 'true',
                    'collapse' => 'show'
                    );
                return $data;
            }
            $no++;
        }
    }
    
    $data = array(
        'menu'     => '',
        'expanded' => 'false',
        'collapse' => ''
        );
    return $data;
}

function set_active_submenu($uri)
{
    $uris = service('uri');
    $uriSegment = strtolower($uris->getSegment(1).'/'.$uris->getSegment(2));
    $no=0;
    foreach ($uri as $u) {
        if ($uriSegment == $uri["$no"]) {
            return 'active';
        }
        $no++;
    }
}

function set_active_nav($uri)
{
    $uris = service('uri');
    $uriSegment = strtolower($uris->getSegment(1));
    $no=0;
    foreach ($uri as $u) {
        if ($uriSegment == $uri["$no"]) {
            return 'nav-menu-header';
        }
        $no++;
    }
    return '';
}