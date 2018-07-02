<?php

function config($key = '')
{
    $config = [
        'name' => 'LifeSnaps',
        'template_path' => 'layouts',
        'content_path' => 'pages',
        'uploads_path' => 'uploads/',
        'pretty_uri' => false,
        'version' => 'v1.0',
        'dbhost' => 'localhost',
        'dbuser' => 'root',
        'dbpass' => '',
        'dbname' => 'lifesnaps',
        'debug' => true
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
