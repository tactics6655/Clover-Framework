<head>
    <title>500 Error</title>
</head>

<body>
    <div id="debugger">
        <div id="header">
            <div id="file">500 Error (<?= $className ?>) > <?= $file ?>:<?= $line ?></div>
            <div id="message"><?= $message ?></div>
        </div>

        <div id="trace">
            <? /** @var Clover\Classes\Debug\TraceObject $trace */ ?>
            <? foreach ($traces as $trace) : ?>
                <div class="item">
                    <div class="short_wrap">
                        <a class="short_name">
                            <b><?= $trace->getText() ?></b>
                        </a>
                    </div>
                    <? if ($trace->hasAnnotation()) : ?>
                        <a class="annotation"><?= $trace->getAnnotation() ?></a>
                    <? endif; ?>
                    <div class="long_wrap">
                        <?= $trace->hasFile() ? sprintf('%s', $trace->getFile()) : $trace->getClass() ?><? if ($trace->hasClass() && $trace->hasLine()) : ?>:<a class="line"><?= $trace->getLine() ?></a><? endif; ?>
                    </div>
                    <? if ($trace->hasCode()) : ?>
                        <pre class="code"><?= $trace->getCode() ?></pre>
                    <? endif; ?>
                    <? if ($trace->hasComment()) : ?>
                        <div class="comment">
                            <?= $trace->getCommentTag() ?>
                        </div>
                    <? endif; ?>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</body>


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
        background-image: linear-gradient(135deg, #eee, #f3f3f3 25%, transparent 25%, transparent 50%, #f3f3f3 50%, #f3f3f3 75%, transparent 75%, transparent);
        background-size: 8px 8px;
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
        border-top: 1px dashed #868181;
    }

    #debugger>#trace>.item>.long_wrap {
        padding-top: 4px;
        font-size: 11px;
        color: #5d5d5d;
        padding: 12px 11px;
        line-height: 11px;
    }

    #debugger>#trace>.item>.short_wrap {
        font-size: 11px;
        line-height: 11px;
        border-bottom: 1px solid #e4e4e4;
        padding: 12px;
        background-color: #353535;
        background-image: linear-gradient(147deg, #000000, #070707 25%, transparent 25%, transparent);
        background-repeat: no-repeat;
        border-top: 4px solid #4384ae;
        background-size: 168px 38px;
    }

    #debugger>#trace>.item>.short_wrap>.short_name {
        font-weight: bold;
    }

    #debugger>#trace>.item>.short_wrap>.short_name>b {
        font-size: 11px;
        line-height: 11px;
        font-weight: 500;
        color: #fff;
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
        padding: 10px 7px;
        background-color: #353535;
        line-height: 12px;
        font-size: 12px;
        background-image: linear-gradient(135deg, #000, #292929 25%, transparent 25%, transparent 50%, #292929 50%, #292929 75%, transparent 75%, transparent);
        background-size: 8px 8px;
    }

    #debugger>#header>#message {
        color: #393939;
        text-wrap: balance;
        padding: 40px 19px;
        font-size: 20px;
        text-shadow: 2px 2px 3px #c1c1c1;
        line-height: 16px;
        font-weight: bold;
        background-image: linear-gradient(135deg, #eee, #dedede 25%, transparent 25%, transparent 50%, #dedede 50%, #dedede 75%, transparent 75%, transparent);
        background-size: 8px 8px;
    }

    .code {
        box-shadow: 0px 0px 7px 0px gainsboro;
        border: 1px solid #c3c3c3;
        white-space: pre-wrap;
        background-color: #f0f0f0;
        margin: 0px 10px 10px 10px;
        padding: 10px;
        color: #424242;
        font-size: 11px;
        background-image: linear-gradient(135deg, #eee, #dedede 25%, transparent 25%, transparent 50%, #dedede 50%, #dedede 75%, transparent 75%, transparent);
        background-size: 8px 8px;
    }

    .highlight {
        background-color: #f3ff86;
        display: -webkit-box;
        color: red;
        font-weight: bold;
        padding: 3px 0px;
        border-radius: 5px;
        border: 1px dashed #f07979;
    }

    .annotation {
        white-space: break-spaces;
        padding: 10px;
        display: block;
        background-color: #e5f2f9;
        font-size: 11px;
        line-height: 12px;
        border-bottom: 1px dotted #cbad0e;
    }
</style>