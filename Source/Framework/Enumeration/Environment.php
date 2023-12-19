<?php

namespace Xanax\Framework\Enumeration;

abstract class Environment
{
    const ERROR_REPORTING_LEVEL = 'error_reporting_level';
    const DISPLAY_ERRORS = 'display_errors';
    const TIMEZONE_ID = 'timezone_id';
    const DISPLAY_STARTUP_ERRORS = 'display_startup_errors';
    const HYPERTEXT_PREPROCESSOR = 'hypertext_preprocessor';
    const VERSION = 'version';
    const MAXIMUM_POST_SIZE = 'max_post_size';
    const MAXIMUM_UPLOAD_FILE_SIZE = 'max_upload_file_size';
    const ALLOWED_SHORT_OPEN_TAG = 'allowed_short_open_tag';
    const ALLOWED_UPLOAD_FILE = 'allowed_upload_file';
    const SESSION_USE_COOKIES = 'session_use_cookies';
    const MAXIMUM_INTEGER_SIZE = 'maximum_integer_size';
    const SERVER = 'server';
    const BUILT_OPERATION_SYSTEM = 'built_operation_system';
    const SOFTWARE = 'software';
    const HOME_PATH = 'home_path';
}
