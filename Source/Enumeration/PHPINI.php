<?php

namespace Xanax\Enumeration;

abstract class PHPINI
{

    const SESSION_USE_COOKIES = 'session.use_cookies';

    const SESSION_COOKIE_NAME = 'session.name';

    const MAX_POST_DATA_SIZE = 'post_max_size';

    const MAX_UPLOAD_FILESIZE = 'upload_max_filesize';

    const MAX_UPLOAD_FILE_NUMBER = 'max_file_uploads';

    const ALLOW_FILE_UPLOADS = 'file_uploads';

    const UPLOAD_TEMPORARY_DIRECTORY = 'upload_tmp_dir';

    const ALLOW_URL_FILE_OPEN = 'allow_url_fopen';

    const ALLOW_INCLUDE_URL = 'allow_url_include';
    
    const DEFAULT_USER_AGENT = 'user_agent';
    
    const DEFAULT_SOCKET_TIMEOUT = 'default_socket_timeout';

    const ALLOW_SHORT_OPEN_TAG = 'short_open_tag';

    const SESSION_AUTOMATIC_START = 'session.auto_start';

    const SESSION_COOKIE_LIFETIME = 'session.cookie_lifetime';

    const SESSION_COOKIE_ADD_HTTPONLY_FLAG = 'session.cookie_httponly';
    
    const MULTIBYTE_STRING_INTERNAL_LANGUAGE = 'mbstring.language';

    const DISPLAY_STARTUP_ERRORS = 'display_startup_errors';

    const DEFAULT_CHARACTERSET = 'default_charset';

    const DEFAULT_MIETYPE = 'default_mimetype';

    const ENABLE_POST_DATA_READING = 'enable_post_data_reading';

    const REPORT_MEMORYLEAKS = 'report_memleaks';

    const IGNORE_REPEATED_ERRORS = 'ignore_repeated_errors';

    const DISPLAY_ERRORS = 'display_errors';

    const ERROR_REPORTING_VALUES = 'error_reporting';

    const MEMORY_LIMIT = 'memory_limit';

    const MAX_MULTIPART_BODY_PARTS = 'max_multipart_body_parts';

    const MAX_INPUT_VARIABLES = 'max_input_vars';

    const MAX_EXECUTION_TIME = 'max_execution_time';

}
