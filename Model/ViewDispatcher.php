<?php

namespace schoolyard\Model;

class ViewDispatcher
{

    public function dispatchView($key) {
        if(!isset($_SESSION)){
             session_start();
        }
        if(strtolower($_SESSION['permission']) === strtolower($key)){
            header('Location: http://localhost/schoolyard/'.$key.'/'.$key.'View');
           
        } else {
            throw new NotFoundException("No matching value committed: $key");
        }

    }

   
    
}