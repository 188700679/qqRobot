<?php
/**
 * User: 木鱼
 * Date: 2020/3/11 7:40
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\exception\ParamsWrongException;
use QQRobot\ResolutionObserver;
use Throwable;

class TextTranslate implements ResolutionObserver{

    private $baseApi='https://api.ai.qq.com/fcgi-bin/nlp/nlp_texttranslate';

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
        if(isset($event->isAt) && $event->atWho==$event->self_id){

            if(preg_match("/^[\x{4e00}-\x{9fa5}]+/u",$event->message,$chinese)){
                $msg='';
                switch($chinese[0]){
                    case '翻译':

                        $msg=trim(substr($event->message,strlen($chinese[0])));
                        $msg=$this->translate($msg);
                    break;
                }



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



        }

        return null;
    }

    private function translate($msg){

        if(!$msg){
            return '';
        }
        if(preg_match("/^[\x{4e00}-\x{9fa5}]+/u",$msg,$chinese)){
            $source='zh';
            $target='en';
        }else{
            $source='en';
            $target='zh';
        }

        $appkey = $this->aiApiKey;

        $params = array(
            'app_id'     => $this->aiApiID,
            'time_stamp' => strval(time()),
            'nonce_str'  => strval(rand()),
            'source'       =>$source,
            'target'       =>$target,
            'text'       => $msg,
        );
        $params['sign'] = getReqSign($params, $appkey);

        // 执行API调用
        $res=json_decode(doHttpPost($this->baseApi, $params));

        //commonLog('',json_encode($res));


        switch($res->ret){
            case 0:
                $msg=$res->data->target_text;
            break;
            default:
                $msg='有点深奥,我要好好想想,你稍后再来吧QAQ';
            break;
        }
        return $msg;

    }

}