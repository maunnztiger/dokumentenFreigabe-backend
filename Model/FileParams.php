<?php

namespace dokumentenFreigabe\Model;
use dokumentenFreigabe\ffmpeg;
use dokumentenFreigabe\DataLayer\Model;

class FileParams
{

    private $fileNames;
    private $duration;
    private $increasedPlays;
    private $plays;
    private $screenshot;

    public function getFileNames($path)
    {
        $files = scandir($path);

        foreach ($files as $key => $file) {
            if ($files[$key] !== "." && $files[$key] !== ".." && strpos($files[$key], '.jpg') === false) {
                $this->fileNames[] = trim($files[$key], '.mp4');
            }
        }

        return $this->fileNames;
    }
     /**
     * 
     */
    public function getVideoDurationTime($filepath){
       
        //var_dump($filepath);
        $dur = shell_exec("ffmpeg -i ".$filepath.".mp4"." 2>&1");
      
        if(preg_match("/: Invalid /", $dur)){
            return false;
         }
         preg_match("/Duration: (.{2}):(.{2}):(.{2})/", $dur, $this->duration);
         if(!isset($this->duration[1])){
            return false;
         }
         return $this->duration[1].":".$this->duration[2].":".$this->duration[3];
     }

     
     public function getPlays($videoName){
        $model = new Model();
        return(!empty($this->plays = $model->select('plays')->from('video')
        ->where('video_name', ':name')
        ->executeQuery(':name', $videoName)->as_object()))?$this->plays = $model->select('plays')->from('video')
        ->where('video_name', ':name')
        ->executeQuery(':name', $videoName)->as_object()->plays:0;
     
    
     }

     public function setPlays($videoName){
        $numberOfPlays = $this->getPlays($videoName);
        if(is_int($numberOfPlays)){
            $this->increasedPlays = $numberOfPlays+1;
            var_dump($this->increasedPlays);
        } else {
            return false;
        }
     }

     public function savePlays($videoName){
        $this->setPlays($videoName);
            $model = new Model();
            $model->update('video')->set(array(
                'plays'
            ),
            array(
                ':plays')
            )->where('video_name', ':name')
            ->executeQuery(
                array(
                ':plays',
                ':name'
                ),
                array(
                    $this->increasedPlays,
                    $videoName
                )
            );
          
      
        
        
     }

     

     public function getScreenshot($videpath, $name){
        $time = 10;
        $infile = "C:\\xampp\\htdocs\\assets\\Detroit.mp4";
        $thumbnail = "C:\\xampp\\htdocs\\assetsDetroit.jpg";
        
        $cmd = sprintf(
            "ffmpeg -i %s -an -ss %s.001 -y -f mjpeg %s 2>&1",
               
               $infile, $time, $thumbnail
        ); 
        
        $img = exec($cmd);
        var_dump($img);
        
     }

     public function getDocxFileNames($path){
       
        
            $files = scandir($path);
    
            foreach ($files as $key => $file) {
                if ($files[$key] !== "." && $files[$key] !== ".." && strpos($files[$key], '.jpg') === false) {
                    $this->fileNames[] = trim($files[$key], '.docx');
                }
            }
    
            return $this->fileNames;
        
     }

}
