<?php
/**
 * User: 木鱼
 * Date: 2020/3/11 13:21
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;

class PrivateMsg implements ResolutionObserver{


    public  function init($event,$sender){
        if(isset($event->message_type) && $event->message_type=='private'){
            $this->privateMsg($event);
        }


    }


    private function privateMsg($event){

        //来自群组的临时对话
        if(isset($event->sub_type) && $event->sub_type=='group'){

            $msg="你好,我是代管的笨笨机器人,请发送'简历',机器人作者马上将简历发送给您,\r看到消息会在第一时间回复您\r谢谢!";

            $client=new Client();
            $client->on('msg',function()use($event){
                $msg=$event->user_id.'通过群私聊你说: '.$event->message;
                return
                    ['msg'=>$msg,'group'=>false,'qq'=>QQRobotConst::MASTER_QQ];
            });


            //主动发消息,不依赖上文
            //回敬,临时对话暂时不可用
//            $client=new Client($event);
//            $client->on('back',function()use($msg){
//                $emoji=rand(128512,128588);;
//                return
//                    ['msg'=>$msg,'emoji'=>$emoji,];
//            });

        }

        //回复好友
        if(isset($event->post_type) && $event->post_type=='message'){
            $msg="你好,我是代管的笨笨机器人,作者马上将简历发送给您,\r看到消息会在第一时间回复您\r或者您也可以点击http://139.224.101.36:81/meiyu+php.pdf\r谢谢!";

            $client=new Client($event);
            $client->on('back',function()use($msg){
                return
                    ['msg'=>$msg];
            });

            $client=new Client();
            $client->on('msg',function()use($event){
                $msg=$event->user_id.'通过群私聊你说: '.$event->message;
                return
                    ['msg'=>$msg,'group'=>false,'qq'=>QQRobotConst::MASTER_QQ];
            });
        }

    }
}