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
        $sql = "SELECT member_state FROM replication_group_members LIMIT 1";
        
        return $this->db->open($sql);
    }
    
    private function restartReplication()
    {
        $this->stopReplication();
        $this->startReplication();
    }
    
    private function stopReplication()
    {
        $sql = "STOP GROUP_REPLICATION";
        $this->db->execute($sql, null);
        echo "Stop Replication Group";
    }
    
    private function startReplication()
    {
        $sql = "START GROUP_REPLICATION";
        $this->db->execute($sql, null);
        echo "Start Replication Group";
    }
    
    public function autoChecker()
    {
        $serverStatus = new ServerStatus();
        $status = $this->checkStatus();
        if (!empty($status)) {
            switch ($status[0]['member_state']) {
                case "ERROR" :
                    if($serverStatus->getData()!='error') {
                        $serverStatus->setData('error');
                        SimpleTelegram::sentMessage($this->config['telegram']['token'], $this->config['telegram']['chat_id'], 'Error');
                        $this->restartReplication();
                    }
                    break;
                case "OFFLINE" :
                    if($serverStatus->getData()!='offline') {
                        $serverStatus->setData('offline');
                        SimpleTelegram::sentMessage($this->config['telegram']['token'], $this->config['telegram']['chat_id'], 'Offline');
                        $this->startReplication();
                    }
                    break;
                default:
                    if($serverStatus->getData()!='normal') {
                        $serverStatus->setData('normal');
                        SimpleTelegram::sentMessage($this->config['telegram']['token'], $this->config['telegram']['chat_id'], 'Back to Normal');
                    }
                    break;
            }
        } else {
            echo "Nothing To Start";
        }
    }
}