<?php

namespace Neko\Enumeration;

abstract class UploadedFileErrorMessage
{
    const UPLOAD_ERR_OK             = 'There is no error, the file uploaded with success.';

    const UPLOAD_ERR_INI_SIZE       = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';

    const UPLOAD_ERR_FORM_SIZE      = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';

    const UPLOAD_ERR_PARTIAL        = 'The uploaded file was only partially uploaded.';

    const UPLOAD_ERR_NO_FILE        = 'No file was uploaded.';

    const UPLOAD_ERR_NO_TMP_DIR     = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';

    const UPLOAD_ERR_CANT_WRITE     = 'Failed to write file to disk. Introduced in PHP 5.1.0.';

    const UPLOAD_ERR_EXTENSION      = 'File upload stopped by extension. Introduced in PHP 5.2.0.';

    const UPLOAD_ERR_UNKNOWN      = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
}
