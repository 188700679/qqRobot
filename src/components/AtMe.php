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
            $msg=$this->checkChar($event);


            $client=new Client($event);

            $client->on('back',function()use($msg){
                $emoji=rand(128512,128588);;
                return
                    ['msg'=>$msg,'emoji'=>$emoji,'at'=>'at'];
            });
        }

        return null;

    }


    private function checkChar($event){

        if(!$event->message){
            return $msg="阁下找我有啥事吗?有事记得at我,一定要加上指令哦";
        }

        if(strpos($event->message,'辛苦')!==false){
            return $msg="不辛苦,您才幸苦了!";

        }

        if(strpos($event->message,'大佬')!==false){
            return $msg="人家才不是大佬嘤嘤嘤!QAQ!";

        }

        $char=['6','牛逼','牛皮','厉害'];

        foreach($char as $v){
            if(strpos($event->message,$v)!==false){
                return $msg="不要这样子嘛,我有点不好意思了!QAQ!";
            }
        }

        $fuck=Dictionary::fuck();
        $isFuck=false;
        //脏话检测
        foreach($fuck as $v){
            if(strpos($event->message,$v)!==false){
                $isFuck=true;
                break;
            }
        }

        if($isFuck){
            return $msg=Dictionary::returnFuck();
        }

        return Dictionary::iDonKnow()."\r如果需要帮助,请@我,并输入帮助,例如'@solo 帮助'\r";

    }


}