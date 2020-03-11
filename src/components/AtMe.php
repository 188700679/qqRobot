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
        if(isset($event->isAt) && $event->atWho==$event->self_id){

            $msg=$this->checkChar($event);
            if($msg){
                $client=new Client($event);
                $client->on('back',function()use($msg){
                    $emoji=rand(128512,128588);;
                    return
                        ['msg'=>$msg,'emoji'=>$emoji,'at'=>'at'];
                });
                exit(__CLASS__."结束,不需要往下执行了");
            }

        }

        return null;

    }


    private function checkChar($event){
        $msg='';

        if(!$event->message){
            return $msg="阁下找我有啥事吗?有事记得at我,一定要加上指令哦";
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



        $msg=$this->isHelp($event);


        return $msg;

    }



    private function isHelp($event){

        if($event->message=='玩游戏'){
            $client=new Client($event);
            $client->on('back',function(){
                return
                    ['at'=>'at','img'=>'jtxny.gif'];
            });
            exit(__CLASS__."结束,不需要往下执行了");
        }


        if(strpos($event->message,'女朋友')!==false){
            $client=new Client($event);
            $client->on('back',function(){
                $r=rand(1,5);
                $img='nv'.$r.'.jpg';
                return
                    ['at'=>'at','img'=>$img];
            });
            exit(__CLASS__."结束,不需要往下执行了");
        }

        if($event->message=='帮助'){

            $msg=<<<EOT
欢迎使用命令帮助,记得所有帮助都需要at我哦 QAQ
 
翻译         例:翻译 love you
天气         例:成都今天天气如何
玩游戏       例:玩游戏
女朋友       例:女朋友随便来一个      

EOT;
            return $msg;
        }



    }







}