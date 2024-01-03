<?php

namespace Xanax\Enumeration;

abstract class AWSACLFlag
{
    const ACL_PRIVATE = 'private';

    const ACL_PUBLIC_READ = 'public-read';

    const ACL_PUBLIC_READ_WRITE = 'public-read-write';

    const ACL_AUTHENTICATED_READ = 'authenticated-read';

}
