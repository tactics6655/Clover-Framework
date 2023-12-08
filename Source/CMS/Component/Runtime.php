<?php

namespace Xanax\CMS\Component;

use Xanax\Classes\OperationSystem;

class Runtime
{
    protected $options;

    protected $environment;

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->setDefaultOptions();
        $this->setEnvironmentVariables();

        $this->applyOptions();
    }

    protected function applyOptions()
    {
        OperationSystem::setErrorReportingLevel($this->options['error_reporting_level']);
        OperationSystem::setDisplayErrors($this->options['display_errors']);
        OperationSystem::setDefaultDateTimeZone($this->options['timezone_id']);
        OperationSystem::setDisplayStatupErrors($this->options['display_startup_errors']);
    }

    protected function setDefaultOptions()
    {
        $this->options['error_reporting_level'] = $this->options['error_reporting_level'] ?? E_ALL & ~E_NOTICE;
        $this->options['display_errors'] = $this->options['display_errors'] ?? True;
        $this->options['timezone_id'] = $this->options['timezone_id'] ?? 'Asia/Seoul';
        $this->options['display_startup_errors'] = $this->options['display_startup_errors'] ?? True;
    }

    protected function setEnvironmentVariables()
    {
        $this->environment = array();
        $this->environment['php'] = array();
        $this->environment['php']['version'] = OperationSystem::getPHPVersion();
        $this->environment['php']['max_post_size'] = OperationSystem::getMaxPostSize();
        $this->environment['php']['max_upload_file_size'] = OperationSystem::getMaxUploadFileSize();
        $this->environment['php']['allowed_short_open_tag'] = OperationSystem::isShortOpenTagAllowed();
        $this->environment['php']['allowed_upload_file'] = OperationSystem::isFileUploadAllowed();
        $this->environment['php']['session_use_cookies'] = OperationSystem::isSessionUseCookies();
        $this->environment['php']['maximum_integer_size'] = OperationSystem::getMaximumIntergerSize();
        
        $this->environment['server'] = array();
        $this->environment['server']['built_operation_system'] = OperationSystem::getBuiltOperationSystemString();
        $this->environment['server']['software'] = OperationSystem::getServerSoftware();
        $this->environment['server']['home_path'] = OperationSystem::getHomePath();
    }
}