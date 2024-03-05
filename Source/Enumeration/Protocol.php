<?php

namespace Clover\Enumeration;

enum Protocol: string
{
    case FILE = "file://";

    case PHAR = "phar://";

    case TEXTMATE = "txmt://";

    case MAC_VIM = "mvim://";

    case EMACS = "emacs://";

    case SUBLIME = "subl://";

    case PHPSTORM = "phpstorm://";

    case ATOM = "atom://";

    case VISUAL_STUIO_CODE = "vscode://";
}
