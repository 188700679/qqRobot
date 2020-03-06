<?php
/**
 * User: 木鱼
 * Date: 2020/3/6 16:08
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;

class Join implements ResolutionObserver{

    public function init($event,$sender) {

        //自己离群
        if(isset($event->notice_type)){

            if($event->user_id!=QQRobotConst::SELF_QQ
                &&
                ($event->sub_type==QQRobotConst::APPROVE)
                &&
                ($event->notice_type==QQRobotConst::GROUP_INCREASE)){
                $msg=<<<EOT
 
 嘿嘿,你好,欢迎来到本群,我是本群小助手,请@我,并输入"帮助"
可以查看本人的帮助命令!
机器人是由php编写,本人github被封了,整理好就发布出源码
祝你玩得开心
EOT;
                $client=new Client($event);
                $client->on('back',function()use($msg){
                    return
                        ['msg'=>$msg,'emoji'=>'128552','at'=>'at'];

                });

            }
        }

        return null;
    }

}