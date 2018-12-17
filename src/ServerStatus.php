<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2018-12-17
 * Time: 08:01
 */

namespace Replication;


class ServerStatus
{
    private $fileName;
    
    public function __construct()
    {
        $this->fileName = 'server_status.log';
    }
    
    public function getData(){
        $myFile = fopen($this->fileName, "r") or die("Unable to open file!");
        $serverStatus = fgets($myFile);
        fclose($myFile);
        return $serverStatus;
    }
    
    public function setData($status){
        $myFile = fopen($this->fileName, "w") or die("Unable to open file!");
        fwrite($myFile, $status);
        fclose($myFile);
    }
    
}