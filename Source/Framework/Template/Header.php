<?php
use Xanax\Classes\HTML\Handler as HTMLHandler;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php foreach($scriptMap as $resource):?>
    <?=HTMLHandler::generateElement('script', '', $resource);?>
<?php endforeach;?>

<?php foreach($cssMap as $resource):?>
    <?=HTMLHandler::generateElement('link', '', $resource);?>
<?php endforeach;?>

    <title><?=$title;?></title>
</head>