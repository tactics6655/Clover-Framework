<?php

namespace Neko\Enumeration;

abstract class LockMode
{
    const ACQUIRE_SHARED_LOCK = 'r';

    const ACQUIRE_EXCLUSIVE_LOCK = 'r';

    const RELEASE_LOCK = 'r';
}
