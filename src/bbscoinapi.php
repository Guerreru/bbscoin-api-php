<?php
/***************************************************************************
 *
 *   BBSCoin Api for PHP
 *   Author: BBSCoin Foundation
 *   
 *   Website: https://bbscoin.xyz
 *
 ***************************************************************************/
 
/****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

class BBSCoinApi {

    private static $online_api_site_id  = '';
    private static $online_api_site_key = '';

    // Send Request
    public static function getUrlContent($url, $data_string) {
        $ch = curl_init();

        if (self::$online_api_site_id && self::$online_api_site_key) {
            $sign = self::sign($data_string);
            $url_suff = 'site_id='.self::$online_api_site_id.'&sign='.$sign['sign'].'&ts='.$sign['ts'];
            if (strpos($url, '?') === false) {
                $url .= '?'.$url_suff;
            } else {
                $url .= '&'.$url_suff;
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BBSCoin');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $data;
    }

    // Generate Sign
    public static function sign($data_string) {
        $ts = time();
        $sign = hash_hmac('sha256', $data_string.$ts, self::$online_api_site_key);
        return array(
            'sign' => $sign,
            'ts' => $ts
        );
    }

    // Send Transaction
    public static function sendTransaction($walletd, $address, $real_price, $sendto) {
        $req_data = array(
          'params' => array(
              'anonymity' => 5,
              'fee' => 50000000,
              'unlockTime' => 0,
              'changeAddress' => $address,
              "transfers" => array(
               0 => array(
                    'amount' => $real_price,
                    'address' => $sendto,
                )
              )
          ),
          "jsonrpc" => "2.0",
          "method" => "sendTransaction"
        );

        $result = self::getUrlContent($walletd, json_encode($req_data)); 
        $rsp_data = json_decode($result, true);
        
        return $rsp_data;
    }

    // Get Status
    public static function getStatus($walletd) {
        $status_req_data = array(
          "jsonrpc" => "2.0",
          "method" => "getStatus"
        );

        $result = self::getUrlContent($walletd, json_encode($status_req_data)); 
        $status_rsp_data = json_decode($result, true);
        return $status_rsp_data;
    }

    // Get Transaction
    public static function getTransaction($walletd, $transaction_hash) {
        $req_data = array(
          "params" => array(
          	"transactionHash" => $transaction_hash
          ),
          "jsonrpc" => "2.0",
          "method" => "getTransaction"
        );

        $result = self::getUrlContent($walletd, json_encode($req_data)); 
        $rsp_data = json_decode($result, true);

        return $rsp_data;
    }

}

