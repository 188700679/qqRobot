<?php
/**
 * User: 木鱼
 * Date: 2019/12/30 18:46
 * Ps:
 */

namespace QQRobot\components;




use QQRobot\Client;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;

class AtMe implements ResolutionObserver{



    public function init($event,$sender){



        //有人at我
        if(isset($event->isAt) && $event->atWho==QQRobotConst::SELF_QQ){

            $msg='我现在还不懂你在说什么,我现在正在重构,请稍等哦QAQ!';

            if(!$event->isAt){
                $msg="阁下找我有啥事吗?有事记得at我,一定要加上指令哦";

            }

            if(strpos($event->raw_message,'辛苦')!==false){
                $msg="不辛苦,您才幸苦了!";

            }

            if(strpos($event->raw_message,'大佬')!==false){
                $msg="人家才不是大佬嘤嘤嘤!QAQ!";

            }

            $char=['6','牛逼','牛皮','厉害'];

            foreach($char as $v){
                if(strpos($event->raw_message,$v)!==false){
                    $msg="不要这样子嘛,我有点不好意思了!QAQ!";

                }
            }
            $client=new Client($event);
            $client->on('back',function()use($msg){
                return
                    ['msg'=>$msg,'emoji'=>'128552','at'=>'at'];
            });


//            $fuck=Dictionary::fuck();
//            $isFuck=false;
//            //脏话检测
//            foreach($fuck as $v){
//                if(strpos($event->atMsg,$v)!==false){
//                    $isFuck=true;
//                    break;
//                }
//            }
//
//            if($isFuck){
//                $event->replyMsg=Dictionary::returnFuck();
//                return SendMessageEvent::sendBack(QCode::At($event->user_id).$event->replyMsg,$event);
//            }
//
//            $faceID=mt_rand(0,100);
//            if($event->replyMsg=='?'){
//                $event->replyMsg=Dictionary::iDonKnow()."\r如果需要帮助,请@我,并输入帮助,例如'@solo 帮助'\r";
//                $faceID=32;
//            }
//
        }



    }



}