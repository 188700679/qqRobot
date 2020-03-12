<?php
/**
 * User: 木鱼
 * Date: 2020/3/11 9:31
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\exception\ParamsWrongException;
use QQRobot\ResolutionObserver;
use Throwable;

class ListenChar implements ResolutionObserver{

    private $baseApi='https://api.ai.qq.com/fcgi-bin/nlp/nlp_textpolar';

    private $aiApiKey='';

    private $aiApiID='';

    public function __construct($config=[]){
        try{
            $this->aiApiKey=$config['aiApiKey'];
            $this->aiApiID=$config['aiApiID'];
        }catch(Throwable $e){
            throw  (new ParamsWrongException())->aiParamsWrong();
        }
    }

    public function init($event,$sender){

        if(strpos($event->message,'机器人')!==false){
            $dictionaryFuck=Dictionary::fuck();
            foreach($dictionaryFuck as $v){
                if(strpos($event->message,$v)!==false){
                    $msg=Dictionary::returnFuck();
                    $client=new Client($event);

                    $client->on('back',function()use($msg){
                        $emoji=rand(128512,128588);;
                        return
                            ['msg'=>$msg,'emoji'=>$emoji,'at'=>'at'];
                    });
                    exit(__CLASS__."结束,不需要往下执行了");

                }
            }
        }


        $job=Dictionary::job();
        foreach($job as $v){
            if(strpos($event->message,$v)!==false){

                $client=new Client($event);
                $msg="你好,请问是在招聘吗,本人最近在找工作\r请加我好友,并发送'简历',本人会自动发送简历给你,谢谢!\r\r本消息来自QQRobot,这是一个php开源类库,本人是作者,如果有不妥之处,请联系我";
                $client->on('back',function()use($msg){
                    $emoji=128591;
                    return
                        ['msg'=>$msg,'emoji'=>$emoji,'at'=>'at'];
                });
                exit(__CLASS__."结束,不需要往下执行了");

            }
        }


        //该方法不支持并发
//        if(preg_match("/^[\x{4e00}-\x{9fa5}]+/u",$event->message,$chinese)){
//            $this->textPolar($event,$chinese[0]);
//        }

        return null;

    }

    //情感分析
//    private function textPolar($event,$sendMsg){
//        $msg='';
//        $appkey = $this->aiApiKey;
//        $params = array(
//            'app_id'     => $this->aiApiID,
//            'nonce_str'  => strval(rand()),
//            'time_stamp'       => strval(time()),
//            'text'       => $sendMsg,
//        );
//        $params['sign'] = getReqSign($params, $appkey);
//
//        // 执行API调用
//        $res=json_decode(doHttpPost($this->baseApi, $params));
//
//        commonLog('',json_encode($res));
//
//        switch($res->ret){
//            case 0:
//                if($res->data->polar=='-1'){
//                    $msg='你好像心情不太好,如果是这样,可以来找我倾述,我会一直陪着你,记得要@我哦!';
//                }
//
//            break;
//
//
//        }
//
//        if($msg){
//            $client=new Client($event);
//
//            $client->on('back',function()use($msg){
//                $emoji=rand(128512,128588);;
//                return
//                    ['msg'=>$msg,'emoji'=>$emoji,'at'=>'at'];
//            });
//
//        }
//    }



}