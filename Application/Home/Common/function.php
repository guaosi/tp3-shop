<?php
/**
 * Date: 2017/7/17/0017
 * Time: 22:31
 */
function makeAlipay($orderId,$btnName='去支付宝支付'){
    return require './alipay/alipayapi.php';
}
//去除网址
function filterurl($param){
//   获取当前网址
    // $nowUrl=$_SERVER['PHP_SELF'];
      $nowUrl=$_SERVER['REQUEST_URI'];
    //写正则
    $re="/\/$param\/[^\/]+/";
    return preg_replace($re,'',$nowUrl);
}
function showImg($url,$width=0){
    $str='';
    if($width>0)
    {
        $str="<img src='".$url."' width=".$width."px />";
    }
        else
        {
            $str="<img src='".$url."' />";
        }
    echo $str;
}
