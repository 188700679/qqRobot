<?php
/**
 * User: 木鱼
 * Date: 2020/3/1 15:46
 * Ps:
 */


use QQRobot\Server;
const VERSION='1.0';

function debug_resource($value=null){
    if(is_resource($value)){
        return $value;
    }else if(defined('STDOUT')){
        return STDOUT;
    }

    return fopen('php://output','w');
}

function default_user_agent(){
    static $defaultAgent='';

    if(!$defaultAgent){
        $defaultAgent='QQRobot/'.VERSION;
        if(extension_loaded('curl') && function_exists('curl_version')){
            $defaultAgent.=' curl/'.\curl_version()['version'];
        }
        $defaultAgent.=' PHP/'.PHP_VERSION;
    }

    return $defaultAgent;
}


function commonLog($filename='',$content=null){

    if(!$content)
        return false;
    $filename?:$filename="/qqrobot.log";
    $hr="\r========================================\r";
    $path=__DIR__.$filename;
    file_put_contents($path,$hr,FILE_APPEND);
    file_put_contents($path,var_export($content,true),FILE_APPEND);
    file_put_contents($path,$hr,FILE_APPEND);
}


function default_ca_bundle(){
    static $cached=null;
    static $cafiles=[
        // Red Hat, CentOS, Fedora (provided by the ca-certificates package)
        '/etc/pki/tls/certs/ca-bundle.crt',
        // Ubuntu, Debian (provided by the ca-certificates package)
        '/etc/ssl/certs/ca-certificates.crt',
        // FreeBSD (provided by the ca_root_nss package)
        '/usr/local/share/certs/ca-root-nss.crt',
        // SLES 12 (provided by the ca-certificates package)
        '/var/lib/ca-certificates/ca-bundle.pem',
        // OS X provided by homebrew (using the default path)
        '/usr/local/etc/openssl/cert.pem',
        // Google app engine
        '/etc/ca-certificates.crt',
    ];

    if($cached){
        return $cached;
    }

    if($ca=ini_get('openssl.cafile')){
        return $cached=$ca;
    }

    if($ca=ini_get('curl.cainfo')){
        return $cached=$ca;
    }

    foreach($cafiles as $filename){
        if(file_exists($filename)){
            return $cached=$filename;
        }
    }

    throw new \RuntimeException(<<< EOT
在任何公共系统位置都找不到系统CA。
5.6之前的PHP版本未正确配置为使用系统的
默认情况下为CA捆绑包。为了验证对等证书，您需要
提供指向“验证”请求的证书捆绑包的路径
EOT
    );
}

function setConfig($arr){
    if(is_array($arr)){
        return (object)array_map(__FUNCTION__,$arr);
    }
    return $arr;
}


function normalize_header_keys(array $headers){
    $result=[];
    foreach(array_keys($headers) as $key){
        $result[strtolower($key)]=$key;
    }

    return $result;
}

function randStr($length){
    $str=null;


    $strPol="0123456789abcdefghijklmnopqrstuvwxyz";

    $max=strlen($strPol)-1;
    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];
    }

    return $str;
}

function getReqSign($params,$aiApiKey){
    // 1. 字典升序排序
    ksort($params);

    // 2. 拼按URL键值对
    $str='';
    foreach($params as $key=>$value){
        if($value!==''){
            $str.=$key.'='.urlencode($value).'&';
        }
    }

    // 3. 拼接app_key
    $str.='app_key='.$aiApiKey;

    // 4. MD5运算+转换大写，得到请求签名
    $sign=strtoupper(md5($str));
    return $sign;
}

function doHttpPost($url,$params){
    $curl=curl_init();

    $response=false;
    do{
        // 1. 设置HTTP URL (API地址)
        curl_setopt($curl,CURLOPT_URL,$url);

        // 2. 设置HTTP HEADER (表单POST)
        $head=[
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($curl,CURLOPT_HTTPHEADER,$head);

        // 3. 设置HTTP BODY (URL键值对)
        $body=http_build_query($params);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$body);

        // 4. 调用API，获取响应结果
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_NOBODY,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        $response=curl_exec($curl);
        if($response===false){
            $response=false;
            break;
        }

        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        if($code!=200){
            $response=false;
            break;
        }
    }while(0);

    curl_close($curl);
    return $response;
}
