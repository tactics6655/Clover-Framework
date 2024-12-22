<?php

namespace Clover\Classes\StandardPHPLibrary;

class Autoload
{
    public function getFunctions() {
        return spl_autoload_functions();
    }

    public function registFunction(callable|null $callback = null, bool $throw = true, bool $prepend = false) {
        spl_autoload_register($callback, $throw, $prepend);
    }

    public function unregistFunction(callable $callback) {
        spl_autoload_unregister($callback);
    }

    public function setDefaultExtensions(string|null $file_extensions = null) {
        return spl_autoload_extensions($file_extensions);
    }

    public function callAutoloadFunction(string $class) {
        spl_autoload_call($class);
    }
}