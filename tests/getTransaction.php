<?php

require '../src/bbscoinapi.php';

// modify transaction_hash
$transaction_hash = '';

// ok send to request
$rsp_data = BBSCoinApi::getTransaction('http://127.0.0.1:8070/json_rpc', $transaction_hash);
var_dump($rsp_data);
