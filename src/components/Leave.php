<?php
/**
 * User: 木鱼
 * Date: 2020/3/6 15:07
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;

class Leave implements ResolutionObserver{

    public function init($event,$sender){
        //自己离群
        if(isset($event->notice_type)){

            if($event->user_id!=QQRobotConst::SELF_QQ
                &&
                ($event->sub_type==QQRobotConst::LEAVE)
                &&
                ($event->notice_type==QQRobotConst::GROUP_DECREASE)){
                $msg=<<<EOT
群友 $event->user_id 离开了我们，祝它前程似锦,光明无限！
EOT;
                $client=new Client($event);
                $client->on('back',function()use($msg){
                    $emoji=rand(128512,128588);;
                    return
                        ['msg'=>$msg,'emoji'=>$emoji];

                });

            }
        }


    }


}