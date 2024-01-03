<?php

namespace Xanax\Enumeration;

abstract class FileMode
{
    /**
     * Reading from beginning of binary file
     */
    const READ_ONLY = 'r';

    /**
     * Reading and Writing from beginning of file
     */
    const READ_OVERWRITE = 'r+';

    /**
     * Writing file with Truncate
     */
    const WRITE_ONLY = 'w';

    /**
     * Reading and Writing file with Truncate
     */
    const READ_WRITE_TRUNCATE = 'w+';

    /**
     * Append to the end of the file
     */
    const APPEND_WRITE_ONLY = 'a';

    /**
     * Reading or Append to the end of the file
     */
    const APPEND_READ_WRITE = 'a+';

    /**
     * Append to the end of the binary file
     */
    const APPEND_BINARY_WRITE_ONLY = 'ab';

    /**
     * Reading or Append to the end of the binary file
     */
    const APPEND_BINARY_READ_WRITE_ONLY = 'ab+';

    /**
     * Reading from beginning of binary file
     */
    const READ_BINARY_ONLY = 'rb';

    /**
     * Reading and Writing from beginning of binary file
     */
    const READ_BINARY_TRUNCATE = 'rb+';

    /**
     * Writing binary file with Truncate
     */
    const WRITE_BINARY_ONLY = 'wb';

    /**
     * Reading and Writing binary file with Truncate
     */
    const WRITE_BINARY_TRUNCATE = 'wb+';

    const NEW_WRITE_ONLY = 'x';

    const NEW_READ_WRITE_ONLY = 'x+';
}
