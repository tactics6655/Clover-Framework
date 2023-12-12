<?php

namespace Xanax\Framework\Component;

class Resource
{

    private $adopted_css_files;

    private $adopted_script_files;

    private $adopted_body_css_files;

    private $adopted_body_script_files;

    private $meta_tags;

    private $title;

    public function __construct()
    {
        $this->adopted_css_files = [];
        $this->adopted_script_files = [];
        $this->meta_tags = [];
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addGenericCssFile($file)
    {
        $this->adopted_css_files[] = array(
            'rel' => 'stylesheet',
            'href' => $file,
            'type' => 'text/css',
            'media' => 'all'
        );
    }

    public function addGenericJavascriptFile($file)
    {
        $this->adopted_script_files[] = array('src' => $file);
    }

    public function addJavascriptFile($map)
    {
        $this->adopted_script_files[] = $map;
    }

    public function addCssFile($map)
    {
        $this->adopted_css_files[] = $map;
    }

    public function addMetaTag($map)
    {
        $this->meta_tags[] = $map;
    }

    public function extract()
    {
        return [
            'cssMap' => $this->adopted_css_files,
            'scriptMap' => $this->adopted_script_files,
            'title' => $this->title
        ];
    }
}