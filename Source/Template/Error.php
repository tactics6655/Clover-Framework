<head>
    <title>500 Error</title>
</head>

<body>
    <div id="fatal">
        <div class="title">
            Error
        </div>
        <div class="message">
            <?= $error_message; ?>
        </div>
        <div class="file">
            <?= $filename; ?>:<?= $linenumber; ?>
        </div>
    </div>
</body>

<style>
    * {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    body {
        background-color: #eee;
    }

    #fatal {
        background-color: #fff;
        width: 600px;
        margin: 0 auto;
        height: 200px;
        margin-top: auto;
        margin-bottom: auto;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 0px 8px 2px #dbdbdb;
        display: flex;
        flex-direction: column;
    }

    .title {
        font-size: 19px;
        color: #a61b4d;
        font-weight: bold;
        flex: 1;
    }

    .message {
        margin-top: 15px;
        font-size: 14px;
        flex: 1;
        font-weight: bold;
        color: red;
    }

    .file {
        margin-top: 15px;
        font-size: 12px;
    }
</style>