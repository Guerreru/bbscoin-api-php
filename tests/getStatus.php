<?php

require '../src/bbscoinapi.php';

// ok send to request
$rsp_data = BBSCoinApi::getStatus('http://127.0.0.1:8070/json_rpc');
var_dump($rsp_data);
