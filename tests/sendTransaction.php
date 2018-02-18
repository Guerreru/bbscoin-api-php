<?php

require '../src/bbscoinapi.php';

// modify wallet address to your address
$from_wallet = '';
$to_wallet = '';

// ok send to request
$rsp_data = BBSCoinApi::sendTransaction('http://127.0.0.1:8070/json_rpc', $from_wallet, 100000000, $to_wallet);
var_dump($rsp_data);
