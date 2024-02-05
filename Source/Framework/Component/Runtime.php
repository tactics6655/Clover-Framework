<?php

namespace Neko\Framework\Component;

use Neko\Framework\Component\Mapper;
use Neko\Framework\Enumeration\Environment;

use Neko\Classes\OperationSystem as OS;
use Neko\Classes\ArrayObject;
use Neko\Classes\HTTP\Request as Request;

use Neko\Classes\Exception\Handler as ExceptionHandler;

use Neko\Classes\File\Functions as FileFunction;

use Neko\Classes\Dom\Document;

use ReflectionMethod;
use Exception;

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
        ExceptionHandler::setExceptionHandler(function (\Throwable $e) {
            $code = $e->getCode();
            $traces = $e->getTrace();
            $className = get_class($e);

            foreach ($traces as $key => $trace) {
                if (!array_key_exists("class", $trace) || empty($trace)) {
                    continue;
                }

                $traces[$key]['absolute_file_path'] = trim(str_replace(dirname(__ROOT__), '', $trace['file'] ?? ''), "/");

                $reflectionClass = new \ReflectionClass($trace['class']);

                $methods = $reflectionClass->getMethods();

                $filtered = array_filter($methods, function ($method) use ($trace) {
                    return $method->name == $trace['function'];
                });

                if (empty($filtered)) {
                    continue;
                }

                $method = $reflectionClass->getMethod($trace['function']);
                
                $shortName = $reflectionClass->getShortName();
                $traces[$key]['short_name'] = $shortName;

                $parameters = $method->getParameters();
                $traces[$key]['parameters'] = $parameters;

                $returnType = $method->getReturnType();
                $traces[$key]['return_type'] = $returnType;

                $comment = $method->getDocComment();
                if (empty($comment)) {
                    continue;
                }

                $comments = array_slice(explode("\n", $comment), 1);
                foreach ($comments as $key => &$comment) {
                    $comment = trim($comment);
                    $comment = rtrim($comment, "/");
                    $comment = ltrim($comment, "*");
                    $comment = trim($comment);

                    if (str_starts_with($comment, "@")) {
                        unset($comments[$key]);
                    } else if (empty($comment)) {
                        unset($comments[$key]);
                    }
                }

                $traces[$key]['comment'] = implode("<br/>", array_values($comments));
            }

            echo FileFunction::getInterpretedContent((__DIR__.'/../Template/Exception.php'), [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'traces' => $traces,
                'className' => $className
            ]);
        });
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

    private function setMapping() :void
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
}
