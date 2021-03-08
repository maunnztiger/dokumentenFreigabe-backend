<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\VideoStream;
use dokumentenFreigabe\Application;
define('PATH', "C:\\xampp\\htdocs\\assets\\BlackbookSessions.mp4");

class BlackbookVideo
{

    public function displayVideo()
    {
       
        $stream = new VideoStream(PATH);
        $stream->start();

    }
}
