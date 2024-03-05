<?php

namespace Clover\Plugin;

use Clover\Classes\ClientURL;

class FFmpeg
{

  public function downloadRemoteVideo($url, $filePath)
  {
    $cmd = sprintf("ffmpeg -i %s -c copy %s", $url, $filePath);
    exec($cmd, $output, $returnVariable);
  }
}
