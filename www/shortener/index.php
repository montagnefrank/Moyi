<?php

/**
 * PHPStringShortener
 *
 * A simple string shortener class to demonstrate how to shorten strings, such
 * as URLs using Base62 encoding.
 *
 * @author Matthias Kerstner <matthias@kerstner.at>
 * @uses PDO
 * @link https://www.kerstner.at/phpStringShortener
 */
require_once ('phpStringShortener.php');

$cmd = $_GET["cmd"];

if ($cmd == 'get') {
    $hash = $_GET['hash'];
    if (!$hash) {
        die('No hash specified');
    }
    $phpSS = new PhpStringShortener();
    $string = $phpSS->getStringByHash($hash);
    $string = $_SESSION['string'];
    echo $string;
    header("Location: ".$_SERVER['HTTP_REFERER']);
} else if ($cmd == 'add') {
    $string = $_GET['string'];
    if (!$string) {
        die('No hash specified');
    }
    $phpSS = new PhpStringShortener();
    $hash = $phpSS->addHashByString($string);
    $_SESSION['hash'] = $hash;
    echo $hash;
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>