<?php
/**
 * User: 木鱼
 * Date: 2020/3/5 9:50
 * Ps:
 */

namespace QQRobot\messageFactory;


use QQRobot\exception\ParamsWrongException;
use QQRobot\lib\MessageConstruct;
use QQRobot\lib\QCode;

class Img implements TypeFactory{




    public static function typeHandle($param,$server){

        $msg='';
        try{
            $param['msg']=isset($param['msg'])?(string)($param['msg']):'';
            $param['emoji']=isset($param['emoji'])?(int)($param['emoji']):0;
            $param['at']=isset($param['at'])?(int)($param['at']):0;
            $param['group']=isset($param['group'])?(bool)($param['group']):true;
            $param['qq']=isset($param['qq'])?(int)($param['qq']):0;

        }catch(\Throwable $e){
            throw (new ParamsWrongException())->errorClientConfig();
        }
        if($param['at']){
            $msg.=QCode::At($param['at']);
        }

        $msg.=$param['msg'];
        if($param['at'] && !$param['group']){
            throw (new ParamsWrongException())->errorAt();
        }
        if(!$param['qq']){
            throw (new ParamsWrongException())->errorQq();
        }



        if($param['emoji']){

            $msg.=QCode::Emoji($param['emoji']);
        }

        return new MessageConstruct($msg,$param['qq'],$param['group']);

    }

    public static  function send(){
        // TODO: Implement send() method.
    }
}