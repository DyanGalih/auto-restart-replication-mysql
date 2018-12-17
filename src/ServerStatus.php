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
    
    public function getData(){
        $myFile = fopen("server_status.log", "r") or die("Unable to open file!");
        $serverStatus = fgets($myFile);
        fclose($myFile);
        return $serverStatus;
    }
    
    public function setData($status){
        $myFile = fopen("server_status.log", "w") or die("Unable to open file!");
        fwrite($myFile, $status);
        fclose($myFile);
    }
    
}