<?php

namespace Clover\Enumeration;

enum LoggingLevel: string
{

    case INFORMATION = "information";

    case ERROR = "error";

    case ASSERT = "assert";

    case VERBOSE = "verbose";

    case WARNING = "warning";

    case DEBUG = "debug";
}
