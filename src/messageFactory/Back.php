<?php
/**
 * User: 木鱼
 * Date: 2020/3/6 8:20
 * Ps:
 */

namespace QQRobot\messageFactory;


use QQRobot\exception\FileNotFindException;
use QQRobot\exception\ParamsWrongException;
use QQRobot\lib\QCode;
use QQRobot\lib\SendMessageEvent;
use QQRobot\QQRobotConst;

class Back implements TypeFactory{

    public static function typeHandle($param,$server):?string {

        $msg='';
        try{
            if(isset($param['at'])){
                $msg.=$param['at']=='at'?QCode::At($server->user_id):(int)($param['at']);
            }

            $param['msg']=isset($param['msg'])?(string)($param['msg']):'';

            $param['img']=isset($param['img'])?(string)trim(($param['img'])):'';

            $msg.=$param['msg'];

            $msg.=isset($param['emoji'])?QCode::Emoji((int)$param['emoji']):'';

        }catch(\Throwable $e){
            throw (new ParamsWrongException())->errorClientConfig();
        }


        if($param['img']){
//            if(!file_exists($param['img'])){
//                throw new FileNotFindException($param['img']);
//            }

            $msg.="\r".QCode::Image(basename($param['img']));
        }


        return $msg;

    }


}