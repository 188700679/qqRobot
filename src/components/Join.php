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
use QQRobot\Server;

class Join implements ResolutionObserver{

    public function init($event,$sender) {

        //加群前验证
        if(isset($event->post_type) && ($event->post_type==QQRobotConst::REQUEST)){
            return $this->addRequest($event);
        }


        //以加群
        if(isset($event->notice_type) && ($event->user_id!=QQRobotConst::SELF_QQ)){
            return $this->hasJoin($event);
        }
    }

    public function addRequest($event){
        if(isset($event->request_type) && $event->request_type==QQRobotConst::GROUP){
            if(isset($event->sub_type)  && $event->sub_type==QQRobotConst::ADD){
                $date=date('Y-m-d H:i:s',$event->time);
                $msg=<<<EOT
管理猿,基佬 $event->user_id 请求加群, 请求时间: $date
他的验证信息是: '$event->comment';
EOT;

                $client=new Client($event);
                $client->on('back',function()use($msg){

                    return
                        ['msg'=>$msg];
                });
            }
        }

        return null;
    }

    private function hasJoin($event){
        if($event->sub_type==QQRobotConst::APPROVE && ($event->notice_type==QQRobotConst::GROUP_INCREASE)){


            $client=new Client();
            $groupInfo=$client->on('groupinfo',function()use($event){
                return
                    ['group_id'=>$event->group_id];
            });

            $admin_count=isset($groupInfo->admin_count)?$groupInfo->admin_count:'未知';
            $create_time=isset($groupInfo->create_time)?date('Y-m-d H:i:s',$groupInfo->create_time):'未知时间';
            $group_name=isset($groupInfo->group_name)?$groupInfo->group_name:'xxx';
            $mans=isset($groupInfo->member_count)?$groupInfo->member_count:'未知';



            $msg=<<<EOT
 
 嘿嘿,你好,欢迎来到'$group_name',我是本群小助手
该机器人是由php编写的一个类库,并且开源到:https://github.com/188700679/qqRobot,
求好心人start+++!!!!我每天都在自我更新.........

本群创建于:$create_time;
共有:$admin_count 名管理猿;
基佬:$mans 名;
妹子?那是一个都没有
祝(huan)你(ying)玩(kai)得(che)开心!
最后请一定要记住,我英俊的容貌
EOT;
            $client=new Client($event);
            $client->on('back',function()use($msg){

                return
                    ['msg'=>$msg,'at'=>'at','emoji'=>128071,'img'=>'guy.png'];
            });

        }
    }

}