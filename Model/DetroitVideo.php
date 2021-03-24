<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\VideoStream;
use dokumentenFreigabe\Application;
define('PATH', "C:\\xampp\\htdocs\\assets\\Detroit.mp4");

class DetroitVideo
{

    public function displayVideo()
    {
       
        $stream = new VideoStream(PATH);
        $stream->start();

    }
}