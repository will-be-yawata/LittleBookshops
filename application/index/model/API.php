<?php
namespace app\index\model;

class API{

    private $api_appid = '92239';
    private $api_secret = '7ccc2907ca59484daf63b1bf9a06c9ef';

    public function sendCode($phone,$code){
        $paramArr = array(
            'showapi_appid'=> $this->api_appid,
            'mobile'=> "$phone",
            'content'=> "{\"code\":\"$code\"}",
            'tNum'=> "T170317004350",
            'big_msg'=> ""
//添加其他参数
        );
        $param = $this->createParam($paramArr,$this->api_secret);
        $url = 'http://route.showapi.com/28-1?'.$param;
        //echo "请求的url:".$url."\r\n";
        $result = file_get_contents($url);
        //echo "返回的json数据:\r\n";
        //print $result.'\r\n';
        $result = json_decode($result);
        //echo "\r\n取出showapi_res_code的值:\r\n";
        //print_r($result->showapi_res_code);
        //echo "\r\n";
        return $result->showapi_res_code;
    }
    public function isbn_getbookinfo($isbn){
        $paramArr = array(
            'showapi_appid'=> $this->api_appid,
            'isbn'=> $isbn
        );
        $param = $this->createParam($paramArr,$this->api_secret);
        $url = 'http://route.showapi.com/1626-1?'.$param;
        //echo "请求的url:".$url."\r\n";
        $result = file_get_contents($url);
        //echo "返回的json数据:\r\n";
        //print $result.'\r\n';
        $result = json_decode($result);
        //echo "\r\n取出showapi_res_code的值:\r\n";
        //print_r($result->showapi_res_code);
        //echo "\r\n";
        return $result;
    }
    public function exp_order($mailNo){
        $paramArr = array(
            'showapi_appid'=> $this->api_appid,
            'com'=> "yunda",
            'nu'=> $mailNo,
            //'senderPhone'=> "",
            //'receiverPhone'=> ""
        );
        $param = $this->createParam($paramArr,$this->api_secret);
        $url = 'http://route.showapi.com/64-19?'.$param;
        $result = file_get_contents($url);
        $result = json_decode($result);
        return (array)$result->showapi_res_body;
    }

    //创建参数(包括签名的处理)
    public function createParam ($paramArr,$showapi_secret) {
        $paraStr = "";
        $signStr = "";
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $signStr .= $key.$val;
                $paraStr .= $key.'='.urlencode($val).'&';
            }
        }
        $signStr .= $showapi_secret;//排好序的参数加上secret,进行md5
        $sign = strtolower(md5($signStr));
        $paraStr .= 'showapi_sign='.$sign;//将md5后的值作为参数,便于服务器的效验
        //echo "排好序的参数:".$signStr."\r\n";
        return $paraStr;
    }

}