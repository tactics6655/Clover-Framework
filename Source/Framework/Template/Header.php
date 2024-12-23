<?php

use Clover\Classes\HTML\Handler as HTMLHandler;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

<? if (isset($scriptMap)) : ?>
    <? foreach ($scriptMap as $resource) : ?>
<?= HTMLHandler::generateElement('script', '', $resource); ?>

        <? endforeach; ?>
    <? endif; ?>

<? if (isset($cssMap)) : ?>
    <? foreach ($cssMap as $resource) : ?>
<?= HTMLHandler::generateElement('link', '', $resource, true); ?>

        <? endforeach; ?>
    <? endif; ?>

    <title><?= $title ?? ""; ?></title>
</head>

<body>