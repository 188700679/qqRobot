<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 3:14
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;

class GroupNotice implements ResolutionObserver{


    public function init($event,$sender){
        $msg='';
        if(isset($event->notice_type) && ($event->notice_type=='group_admin')){

            $msg=$this->setAdmin($event);

        }

        if($msg){
            $client=new Client($event);

            $client->on('back',function()use($msg){
                $emoji=rand(128512,128588);;
                return
                    ['msg'=>$msg,'emoji'=>$emoji];
            });
        }





    }

    //设置我为管理员
    private function setAdmin($event):string {
        if(isset($event->sub_type)){
            if($event->sub_type=='set'){
                return $this->addAdmin($event);

            }

            if($event->sub_type=='unset'){
                return $this->unsetAdmin($event);

            }
        }

        return '';
    }


    private function addAdmin($event){

        if($event->user_id==QQRobotConst::SELF_QQ){
            return '我在本群荣生为管理员,快来恭喜我吧!  '.date('H:i:s',$event->time).'  ';
        }else{
            return "恭喜  $event->user_id 晋升管理员,酸啊! ".date('H:i:s',$event->time);
        }

    }

    private function unsetAdmin($event){

        if($event->user_id==QQRobotConst::SELF_QQ){
            return '我擦,你竟然下了我的管理员,明早三点我们学校门口决一死战吧!臭弟弟   '.date('H:i:s',$event->time);
        }else{
            return "哦豁  $event->user_id 的管理员职位被下了,喜大普奔啊 ".date('H:i:s',$event->time)  ;
        }

    }
}