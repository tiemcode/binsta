<?php

function displayTemplate($template, $array)
{
    if (isset($_SESSION['id'])) {
        $pfp = R::findone('user', 'id = ?', [$_SESSION['id']]);
        $globalPfp = $pfp['profilepic'];
    }
    $loader = new \Twig\Loader\FilesystemLoader('../views/');
    $twig = new \Twig\Environment(
        $loader,
        ['debug' => true]
    );
    $twig->addGlobal('get', $_GET);
    if (isset($_SESSION)) {
        $twig->addGlobal('session', $_SESSION);
    }
    if (isset($_SESSION['id'])) {
        $pfp = R::findone('user', 'id = ?', [$_SESSION['id']]);
        $globalPfp = $pfp['profilepic'];
        $twig->addGlobal('pfp', $globalPfp);
    }
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $twig->display($template, $array);
}
function error($errorNumber, $errorMessage)
{
    http_response_code($errorNumber);
    displayTemplate("error.twig", array("error" => $errorMessage, "number" => $errorNumber));
    exit();
}

function getPath(): array
{
    $path = strtok($_GET['p'], '?');
    while (str_contains($path, '//')) {
        $path = str_replace('//', '/', $path);
    }

    if ($path === '/') {
        return [];
    }

    return explode('/', trim($path, '/'));
}
