<?php

namespace Xanax\Framework\Component;

use Xanax\Classes\OperationSystem;
use Xanax\Framework\Component\Mapper;
use Xanax\Framework\Enumeration\Environment;
use Xanax\Classes\ArrayObject;

class Runtime
{
    protected $options;

    protected $environment;

    public function __construct(array $options = [])
    {
        $this->options = $options ?? [];
        $this->environment = [];

        $this->setDefaultOptions();
        $this->setEnvironmentVariables();

        $this->applyOptions();
        $this->setMapping();
    }

    private function setMapping()
    {
        $mapper = new Mapper();
        $mapper->matchRunner();
    }

    private function setOption($key, $default = null)
    {
        $this->options[$key] = $this->options[$key] ?? $default;
    }

    private function setEnvironment($key, $value)
    {
        $array = is_array($key) ? $key : [$key];
        ArrayObject::setDeep($array, $value);
    }

    protected function applyOptions()
    {
        OperationSystem::setErrorReportingLevel($this->options[Environment::ERROR_REPORTING_LEVEL]);
        OperationSystem::setDisplayErrors($this->options[Environment::DISPLAY_ERRORS]);
        OperationSystem::setDefaultDateTimeZone($this->options[Environment::TIMEZONE_ID]);
        OperationSystem::setDisplayStatupErrors($this->options[Environment::DISPLAY_STARTUP_ERRORS]);
    }

    protected function setDefaultOptions()
    {
        $this->setOption(Environment::ERROR_REPORTING_LEVEL, E_ALL & ~E_NOTICE);
        $this->setOption(Environment::DISPLAY_ERRORS, TRUE);
        $this->setOption(Environment::TIMEZONE_ID, 'Asia/Seoul');
        $this->setOption(Environment::DISPLAY_STARTUP_ERRORS, TRUE);
    }

    protected function setEnvironmentVariables()
    {
        $this->environment[Environment::HYPERTEXT_PREPROCESSOR] = [];
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::VERSION], OperationSystem::getPHPVersion());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_POST_SIZE], OperationSystem::getMaxPostSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_UPLOAD_FILE_SIZE], OperationSystem::getMaxUploadFileSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_SHORT_OPEN_TAG], OperationSystem::isShortOpenTagAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_UPLOAD_FILE], OperationSystem::isFileUploadAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::SESSION_USE_COOKIES], OperationSystem::isSessionUseCookies());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_INTEGER_SIZE], OperationSystem::getMaximumIntergerSize());
        
        $this->environment[Environment::SERVER] = [];
        $this->setEnvironment([Environment::SERVER, Environment::BUILT_OPERATION_SYSTEM], OperationSystem::getBuiltOperationSystemString());
        $this->setEnvironment([Environment::SERVER, Environment::SOFTWARE], OperationSystem::getServerSoftware());
        $this->setEnvironment([Environment::SERVER, Environment::HOME_PATH], OperationSystem::getHomePath());
    }
}