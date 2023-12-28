<?php

namespace Xanax\Framework\Component;

use Xanax\Classes\OperationSystem as OS;
use Xanax\Framework\Component\Mapper;
use Xanax\Framework\Enumeration\Environment;
use Xanax\Classes\ArrayObject;
use Xanax\Classes\HTTP\Router as Router;
use Xanax\Classes\HTTP\Request as Request;

class Runtime
{
    protected $options;

    protected $environment;

    public function __construct(array $options = [])
    {
        $this->options = $options ?? [];
        $this->environment = [];
    }

    public function run()
    {
        $this->setDefaultOptions();
        $this->setEnvironmentVariables();
        $this->applyOptions();

        $this->setMapping();
    }
    
    private function flushResponseData()
    {
        $software = $this->environment[Environment::SERVER][Environment::SOFTWARE];

        if ($software == 'nginx') {
            Request::flushFastCgiResponseData();
        } else if ($software == 'lightspeed') {
            Request::flushLightSpeedResponseData();
        }
    }

    private function setMapping()
    {
        $mapper = new Mapper($this->options, $this->environment);
        $runner = $mapper->matchRunner();
        $runner->run()->printBody();
        $this->flushResponseData();
    }

    private function setOption($key, $default = null)
    {
        $this->options[$key] = $this->options[$key] ?? $default;
    }

    private function setEnvironment($key, $value)
    {
        $array = is_array($key) ? $key : [$key];
        ArrayObject::setDeep($this->environment, $array, $value);
    }

    protected function applyOptions()
    {
        OS::setDisplayErrors($this->options[Environment::DISPLAY_ERRORS]);
        OS::setErrorReportingLevel($this->options[Environment::ERROR_REPORTING_LEVEL]);
        OS::setDefaultDateTimeZone($this->options[Environment::TIMEZONE_ID]);
        OS::setDisplayStatupErrors($this->options[Environment::DISPLAY_STARTUP_ERRORS]);
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
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::VERSION], OS::getPHPVersion());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_POST_SIZE], OS::getMaxPostSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_UPLOAD_FILE_SIZE], OS::getMaxUploadFileSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_SHORT_OPEN_TAG], OS::isShortOpenTagAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_UPLOAD_FILE], OS::isFileUploadAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::SESSION_USE_COOKIES], OS::isSessionUseCookies());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_INTEGER_SIZE], OS::getMaximumIntergerSize());

        $this->environment[Environment::SERVER] = [];
        $this->setEnvironment([Environment::SERVER, Environment::BUILT_OPERATION_SYSTEM], OS::getBuiltOperationSystemString());
        $this->setEnvironment([Environment::SERVER, Environment::SOFTWARE], OS::getMainServerSoftware());
        $this->setEnvironment([Environment::SERVER, Environment::HOME_PATH], OS::getHomePath());
    }
}
