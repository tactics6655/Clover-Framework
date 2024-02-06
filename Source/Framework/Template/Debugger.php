<div id="debugger">
    <div id="memory_usage" class="float_left">
        <?= $memoryUsage ?>
    </div>
    <div id="free_space" class="float_left">
        <?= $freeSpace ?>
    </div>

    <div id="built_operation_system" class="float_right">
        <?= $builtOperationSystem ?> / <?= $serverSoftware ?>
    </div>
    <div id="php_version" class="float_right">
        PHP <?= $phpVersion ?>
    </div>
</div>

<style>
    .float_left {
        float: left;
    }

    .float_right {
        float: right;
    }

    #debugger {
        display: block;
        position: fixed;
        bottom: 0px;
        line-height: 50px;
        height: 50px;
        background-color: #3e3e3e;
        left: 0px;
        width: 100%;
    }

    #debugger>div {
        height: 100%;
        display: inline-block;
        vertical-align: middle;
        text-align: center;
        padding: 0px 13px;
        font-size: 11px;
    }

    #debugger>#memory_usage {
        background-color: #006624;
        color: #fff;
    }

    #debugger>#php_version {
        background-color: #000e53;
        color: #fff;
    }

    #debugger>#server_software {
        background-color: #000000;
        color: #fff;
    }

    #debugger>#built_operation_system {
        background-color: #6b0000;
        color: #fff;
    }

    #debugger>#free_space {
        background-color: #000e53;
        color: #fff;
    }
</style>