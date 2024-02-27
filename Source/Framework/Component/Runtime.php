<?php

namespace Neko\Framework\Component;

use Neko\Framework\Component\Mapper;
use Neko\Framework\Enumeration\Environment;

use Neko\Classes\OperationSystem as OS;
use Neko\Classes\ArrayObject;
use Neko\Classes\HTTP\Request as Request;
use Neko\Classes\Debug\ErrorHandler;

class Runtime
{

    protected $options;

    protected $environment;

    public function __construct(array $options = [])
    {
        $this->options = $options ?? [];
        $this->environment = [];
    }

    /**
     * Execute runtime
     */
    public function run()
    {
        $this->setErrorHandler();

        $this->setDefaultOptions();
        $this->setEnvironmentVariables();
        $this->applyOptions();

        $this->setMapping();
    }

    public function setErrorHandler()
    {
        ErrorHandler::register();
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

    private function setMapping(): void
    {
        $mapper = new Mapper($this->options, $this->environment);
        $runner = $mapper->matchRunner();
        $response = $runner->run();
        if ($response instanceof Response) {
            $response->printBody();
        }

        $this->flushResponseData();
    }

    private function setOption($key, $default = null)
    {
        $this->options[$key] = $this->options[$key] ?? $default;
    }

    private function setEnvironment($key, $value)
    {
        $array = is_array($key) ? $key : [$key];
        ArrayObject::setDeepCopy($this->environment, $array, $value);
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
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR], []);
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::VERSION], OS::getPHPVersion());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_POST_SIZE], OS::getMaxPostSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_UPLOAD_FILE_SIZE], OS::getMaxUploadFileSize());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_SHORT_OPEN_TAG], OS::isShortOpenTagAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::ALLOWED_UPLOAD_FILE], OS::isFileUploadAllowed());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::SESSION_USE_COOKIES], OS::isSessionUseCookies());
        $this->setEnvironment([Environment::HYPERTEXT_PREPROCESSOR, Environment::MAXIMUM_INTEGER_SIZE], OS::getMaximumIntergerSize());

        $this->setEnvironment([Environment::SERVER], []);
        $this->setEnvironment([Environment::SERVER, Environment::BUILT_OPERATION_SYSTEM], OS::getBuiltOperationSystemString());
        $this->setEnvironment([Environment::SERVER, Environment::SOFTWARE], OS::getMainServerSoftware());
        $this->setEnvironment([Environment::SERVER, Environment::HOME_PATH], OS::getHomePath());
    }
    
    public function serialize()
    {
        return serialize(array($this->environment));
    }

}
