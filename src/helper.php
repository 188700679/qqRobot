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

        if(!$content)return false;
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
            return (object) array_map(__FUNCTION__, $arr);
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
