<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 30/11/18
 * Time: 15.38
 */

namespace Replication;

use WebAppId\SimplePDO\Database;

class Replication
{
    private $db;
    private $config;
    
    public function __construct($config)
    {
        $this->db = new Database($config);
        $this->config = $config;
    }
    
    private function checkStatus()
    {
        $sql = "SELECT member_state FROM replication_group_members WHERE  member_host = :member_host";
        
        $obj = new \StdClass();
        $obj->member_host = $this->config["src"]["ip"];
        
        return $this->db->open($sql, $obj);
    }
    
    private function restartReplication(){
        $this->stopReplication();
        $this->startReplication();
    }
    
    private function stopReplication(){
        $sql = "STOP GROUP_REPLICATION";
        $this->db->execute($sql, null);
        echo "Stop Replication Group";
    }
    
    private function startReplication(){
        $sql = "START GROUP_REPLICATION";
        $this->db->execute($sql, null);
        echo "Start Replication Group";
    }
    
    public function autoChecker()
    {
        $status = $this->checkStatus();
        if(!empty($status)){
            if($status[0]['member_state'] == "ERROR"){
                $this->restartReplication();
            }else{
                $this->startReplication();
            }
        }else{
            echo "Nothing To Start";
        }
    }
}