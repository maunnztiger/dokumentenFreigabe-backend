<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\VideoStream;
use dokumentenFreigabe\Application;


class Video
{
    private $path = "C:\\xampp\\htdocs\\assets\\";
    private $fullPath;
    
    public function displayVideo($videoName) {
        
        $this->fullPath = $this->path.$videoName.".mp4";
        var_dump($this->fullPath);
        $stream = new VideoStream($this->fullPath);
        $stream->start();

    }
}
