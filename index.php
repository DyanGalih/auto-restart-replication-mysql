<?php

/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 30/11/18
 * Time: 15.33
 */

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config.php';

use Replication\Replication;


$replication = new Replication($configuration);

while(true) {
    $replication->autoChecker();
    sleep(1);
}