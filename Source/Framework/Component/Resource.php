<?php

namespace Clover\Framework\Component;

use Clover\Classes\File\Functions as FileFunctions;

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
        $this->adoptedCssFiles[] = [
            'rel' => 'stylesheet',
            'href' => $this->appendTimestampToResource($file),
            'type' => 'text/css',
            'media' => 'all'
        ];
    }

    protected function appendTimestampToResource($file)
    {
        if (str_starts_with($file, 'http')) {
            return sprintf("%s", $file);
        }

        $createdDate = FileFunctions::getCreatedDate(__ROOT__ . $file);

        return sprintf("%s?t=%d", $file, $createdDate);
    }

    public function addGenericJavascriptFile($file)
    {
        $this->adoptedScriptFiles[] = ['src' => $this->appendTimestampToResource($file)];
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
