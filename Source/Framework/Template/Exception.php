<head>
    <title>500 Error</title>
</head>

<div id="debugger">
    <div id="header">
        <div id="file">500 Error (<?=$className?>) > <?=$file?>:<?=$line?></div>
        <div id="message"><?=$message?></div>
    </div>

    <div id="trace">
        <?/** @var string[] $trace */?>
        <?foreach ($traces as $trace):?>
            <div class="item">
                <?
                    $index = 0;
                    $arguments = [];
                    /** @var ReflectionType $returnType */
                    $returnType = $trace['return_type'];
                ?>
                <?foreach($trace['args'] ?? [] as $key => $argument):?>
                    <?
                        /** @var ReflectionParameter $parameter */
                        $parameter = $trace['parameters'][$index++];

                        if (is_string($argument)) {
                            $arguments[] = sprintf("<a class=\"type\">$%s = </a><a class=\"normal\">'%s'</a>", $parameter->name, $argument);
                        } else if (is_object($argument)) {
                            $arguments[] = sprintf("<a class=\"type\">$%s = </a><a class=\"class\">%s</a>", $parameter->name, get_class($argument));
                        }
                    ?>
                <?endforeach;?>
                <div class="short_wrap">
                    <a class="short_name"><?=$trace['short_name']?></a><?=$trace['type']?><a class="function"><?=$trace['function']?></a>(<?=join(', ', $arguments);?>)<?=$returnType ? " : <a class=\"return_type\">{$returnType}</a>" : ''?>
                </div>
                <?if(isset($trace['comment'])):?>
                <div class="comment">
                    <?=$trace['comment']?>
                </div>
                <?endif;?>
                <div class="long_wrap">
                    <?=$trace['absolute_file_path'] ? sprintf('%s', $trace['absolute_file_path']) : $trace['class']?><?if(isset($trace['line'])):?>:<a class="line"><?=$trace['line']?></a><?endif;?>
                </div>
            </div>
        <?endforeach;?>
    </div>
</div>

<style>
body {
    padding: 0px;
    margin: 0px;
}
#trace > .item > .comment {
    background-color: #f7f7f7;
    color: #292929;
    padding: 7px 8px;
    font-size: 10px;
    border-bottom: 1px solid #dddddd;
    border-left: 5px solid #adadad;
}
#trace > .item > .long_wrap {
    padding-top: 4px;
    font-size: 11px;
    color: #5d5d5d;
    text-decoration: underline;
    padding: 9px;
    line-height: 11px;
}
#trace > .item > .short_wrap {
    font-size: 11px;
    border-bottom: 1px solid #e4e4e4;
    padding-top: 0px;
    padding-bottom: 4px;
    padding: 5px;
    padding-bottom: 6px;
    border-left: 5px solid #adadad;
    background-color: #e2e2e2;
    text-shadow: 1px 2px 6px #919191;
}
#trace > .item > .short_wrap > .short_name {
    font-weight: bold;
    color: #a30000;
}
.return_type {
    color: #0000FF;
}
.type {
    color: #1F37A8;
}
.normal {
    color: #0000FF;
}
.class {
    color: #2B91AF;
}
code {
    padding: 3px;
    background-color: #eee;
    margin-top: 10px;
    display: block;
    border: 1px solid #c2c2c2;
    font-size:11px;
    white-space: break-spaces;
}
#header {
    background-color: #8d1818;
    color: white;
    white-space: normal;
    line-height: 25px;
    font-size: 11px;
}
#file {
    padding: 9px 7px;
    background-color: #500000;
    line-height: 10px;
    font-size: 10px;
}
#message {
    padding: 25px 17px;
    font-size: 12px;
    line-height: 12px;
    border-left: 7px solid #ba0000;
}
#trace {
    background-color: #f4f4f4;
    padding: 17px;
    font-size: 13px;
}
#trace > .item:first-child {
    background-color:white;
    border-left: 1px solid #d5d5d5;
    border-right: 1px solid #d5d5d5;
    border-top: 1px solid #d5d5d5;
}
#trace > .item:not(first-child) {
    background-color:white;
    border-left: 1px solid #d5d5d5;
    border-right: 1px solid #d5d5d5;
    border-top: 1px dotted #d5d5d5;
}
#trace > .item:last-child {
    border-bottom: 1px solid #d5d5d5;
}
#trace > .item > .short_wrap > .function {
    font-weight: bold;
    color: #a30000;
}
#trace > .item > .long_wrap > .line {
    color: #949494;
}
</style>