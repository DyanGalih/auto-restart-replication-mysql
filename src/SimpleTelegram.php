<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2018-12-17
 * Time: 07:27
 */

namespace Replication;


class SimpleTelegram
{
    public static function sentMessage($token, $chat_id, $message){
        $server_name = exec('hostname');
    
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Mysql Replication On Server ' . $server_name . ' is '. $message
        ];
    
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );
    }
}