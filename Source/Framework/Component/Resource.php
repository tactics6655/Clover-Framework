<?php

namespace Neko\Framework\Component;

class Resource
{

    private $adoptedCssFiles;

    private $adoptedScriptFiles;

    private $adoptedBodyCssFiles;

    private $adoptedBodyScriptFiles;

    private $metaTags;

    private $title;

    public function __construct()
    {
        $this->adoptedCssFiles = [];
        $this->adoptedScriptFiles = [];
        $this->metaTags = [];
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addGenericCssFile($file)
    {
        $this->adoptedCssFiles[] = array(
            'rel' => 'stylesheet',
            'href' => $file,
            'type' => 'text/css',
            'media' => 'all'
        );
    }

    public function addGenericJavascriptFile($file)
    {
        $this->adoptedScriptFiles[] = array('src' => $file);
    }

    public function addJavascriptFile($map)
    {
        $this->adoptedScriptFiles[] = $map;
    }

    public function addCssFile($map)
    {
        $this->adoptedCssFiles[] = $map;
    }

    public function addMetaTag($map)
    {
        $this->metaTags[] = $map;
    }

    public function extract()
    {
        return [
            'cssMap' => $this->adoptedCssFiles,
            'scriptMap' => $this->adoptedScriptFiles,
            'title' => $this->title
        ];
    }
}
