<?php
/**
 * User: 木鱼
 * Date: 2020/3/5 9:50
 * Ps:
 */

namespace QQRobot\messageFactory;


use QQRobot\exception\FileNotFindException;
use QQRobot\exception\ParamsWrongException;
use QQRobot\lib\CQA;
use QQRobot\lib\MessageConstruct;
use QQRobot\lib\QCode;
use QQRobot\QQRobotConst;

class Msg  implements TypeFactory{


    public static function typeHandle($param,$server){


        $msg='';
        try{
            if(isset($param['at'])){
                $msg.=$param['at']=='at'?QCode::At($server->user_id):(int)($param['at']);
            }
            $param['msg']=isset($param['msg'])?(string)($param['msg']):'';
            $param['emoji']=isset($param['emoji'])?(int)($param['emoji']):'';
            $param['group']=isset($param['group'])?(bool)($param['group']):true;
            $param['qq']=isset($param['qq'])?(int)($param['qq']):0;
            $param['at']=isset($param['at'])?(int)($param['at']):0;

            $param['img']=isset($param['img'])?(string)trim(($param['img'])):'';

            if($param['at']){
                $msg.=QCode::At($param['at']);
            }
            $msg.=$param['msg'];

        }catch(\Throwable $e){
            throw (new ParamsWrongException())->errorClientConfig();
        }

        if($param['at'] && !$param['group']){
            throw (new ParamsWrongException())->errorAt();
        }
        if(!$param['qq']){
            throw (new ParamsWrongException())->errorQq();
        }
        if($param['emoji']){
            $msg.=QCode::Emoji($param['emoji']);
        }

        if($param['img']){
//            if(!file_exists($param['img'])){
//                throw new FileNotFindException($param['img']);
//            }

            $msg.="\r".QCode::Image(basename($param['img']));
        }

        return new MessageConstruct($msg,$param['qq'],$param['group']);

    }


}