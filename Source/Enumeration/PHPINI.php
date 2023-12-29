<?php

namespace Xanax\Enumeration;

abstract class PHPINI
{
    const SESSION_USE_COOKIES = 'session.use_cookies';

    const SESSION_COOKIE_NAME = 'session.name';

    const MAXIMUM_POST_DATA_SIZE = 'post_max_size';

    const MAXIMUM_UPLOAD_FILESIZE = 'upload_max_filesize';

    const MAXIMUM_UPLOAD_FILE_NUMBER = 'max_file_uploads';

    const ALLOW_FILE_UPLOADS = 'file_uploads';

    const UPLOAD_TEMPORARY_DIRECTORY = 'upload_tmp_dir';

    const ALLOW_URL_FILE_OPEN = 'allow_url_fopen';

    const ALLOW_INCLUDE_URL = 'allow_url_include';
    
    const DEFAULT_USER_AGENT = 'user_agent';
    
    const DEFAULT_SOCKET_TIMEOUT = 'default_socket_timeout';

    const ALOW_SHORT_OPEN_TAG = 'short_open_tag';

    const SESSION_AUTOMATIC_START = 'session.auto_start';

    const SESSION_COOKIE_LIFETIME = 'session.cookie_lifetime';

    const SESSION_COOKIE_ADD_HTTPONLY_FLAG = 'session.cookie_httponly';
    
    const MULTIBYTE_STRING_INTERNAL_LANGUAGE = 'mbstring.language';

    const DISPLAY_STARTUP_ERRORS = 'display_startup_errors';
}
