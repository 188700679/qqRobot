<?php
/**
 * User: 木鱼
 * Date: 2020/3/2 15:26
 * Ps:
 */

namespace QQRobot\exception;


use Throwable;
include_once __DIR__.'/../helper.php';

class ParamsWrongException extends TransferException{


    public function __construct($str=null) {

        if (is_string($str)) {
            commonLog($str.' is a Wrong parameter type');
            trigger_error($str.' is a Wrong parameter type',E_USER_WARNING);
        }
    }


    public function errorAt(){
        commonLog('','私聊中无法at');
        trigger_error('私聊中无法at',E_USER_WARNING);
    }

    public function errorQq(){
        commonLog('','qq号\群号必填');
        trigger_error('qq号\群号必填',E_USER_WARNING);
    }

    public  function errorClientConfig(){
        commonLog('','client实例里配置错误!');
        trigger_error('client实例里配置错误!',E_USER_DEPRECATED);
    }
}