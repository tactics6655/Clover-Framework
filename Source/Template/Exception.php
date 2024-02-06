<head>
    <title>500 Error</title>
</head>

<div id="debugger">
    <div id="header">
        <div id="file">500 Error (<?= $className ?>) > <?= $file ?>:<?= $line ?></div>
        <div id="message"><?= $message ?></div>
    </div>

    <div id="trace">
        <? /** @var string[] $trace */ ?>
        <? foreach ($traces as $trace) : ?>
            <div class="item">
                <?
                /** @var ReflectionType $returnType */
                $returnType = $trace['return_type'];
                ?>
                <div class="short_wrap">
                    <a class="short_name">
                        <b><?= $trace['full_string'] ?></b>
                    </a>
                </div>
                <div class="long_wrap">
                    <?= $trace['absolute_file_path'] ? sprintf('%s', $trace['absolute_file_path']) : $trace['class'] ?><? if (isset($trace['line'])) : ?>:<a class="line"><?= $trace['line'] ?></a><? endif; ?>
                </div>
                <? if (isset($trace['comment'])) : ?>
                    <div class="comment">
                        <?= $trace['comment'] ?>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach; ?>
    </div>
</div>

<style>
    * {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    body {
        padding: 0px;
        margin: 0px;
    }

    #debugger>#trace {
        background-color: #fefefe;
        padding: 17px;
        font-size: 13px;
        line-height: 13px;
    }
    #debugger>#trace>.item {
        box-shadow: 1px 2px 5px #e2e2e2;
    }
    #debugger>#trace>.item>.comment {
        background-color: #e9e9e9;
        color: #0008d4;
        padding: 6px 6px;
        font-size: 11px;
        line-height: 11px;
        padding: 11px;
    }

    #debugger>#trace>.item>.long_wrap {
        padding-top: 4px;
        font-size: 13px;
        color: #5d5d5d;
        padding: 12px 11px;
        line-height: 13px;
    }

    #debugger>#trace>.item>.short_wrap {
        font-size: 11px;
        line-height: 11px;
        border-bottom: 1px solid #e4e4e4;
        padding: 12px;
        background-color: #f8f8f8;
        color: #000;
        border-radius: 10px 10px 0px 0px;
    }

    #debugger>#trace>.item>.short_wrap>.short_name {
        font-weight: bold;
    }

    #debugger>#trace>.item>.short_wrap>.short_name>b {
        font-size: 13px;
        line-height: 13px;
        font-weight: 500;
        color: #3d3d3d;
    }

    #debugger>#trace>.item>.short_wrap>.return_type {
        color: #0000FF;
    }

    #debugger>#trace>.item>.short_wrap>.type {
        color: #1F37A8;
    }

    #debugger>#trace>.item>.short_wrap>.normal {
        color: #0000FF;
    }

    #debugger>#trace>.item>.short_wrap>.class {
        color: #2B91AF;
    }

    #debugger>#trace>.item>.short_wrap>code {
        padding: 3px;
        background-color: #eee;
        margin-top: 10px;
        display: block;
        border: 1px solid #c2c2c2;
        font-size: 11px;
        white-space: break-spaces;
        line-height: 11px;
    }

    #debugger>#trace>.item:not(:last-child) {
        margin-bottom: 20px;
    }

    #debugger>#trace>.item:first-child {
        background-color: white;
        border-left: 1px solid #c4c4c4;
        border-right: 1px solid #c4c4c4;
        border-top: 1px solid #c4c4c4;
        border-bottom: 1px solid #c4c4c4;
    }

    #debugger>#trace>.item:not(first-child) {
        background-color: white;
        border-left: 1px solid #c4c4c4;
        border-right: 1px solid #c4c4c4;
        border-top: 1px dotted #c4c4c4;
        border-bottom: 1px solid #c4c4c4;
    }

    #debugger>#trace>.item:last-child {
        border-bottom: 1px solid #c4c4c4;
    }

    #debugger>#trace>.item>.short_wrap>.function {
        font-weight: bold;
        color: #a30000;
    }

    #debugger>#trace>.item>.long_wrap>.line {
        color: #949494;
    }
    #debugger>#header {
        background-color: #f0f0f0;
        white-space: normal;
        line-height: 25px;
        border-bottom: 1px solid #dbdbdb;
    }

    #debugger>#header>#file {
        color: white;
        padding: 10px 18px;
        background-color: #353535;
        line-height: 12px;
        font-size: 12px;
    }

    #debugger>#header>#message {
        color: #393939;
        padding: 22px 19px;
        font-size: 16px;
        line-height: 16px;
        font-weight: bold;
    }

</style>