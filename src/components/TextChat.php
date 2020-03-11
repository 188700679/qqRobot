<?php
/**
 * User: 木鱼
 * Date: 2020/3/10 10:44
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\Client;
use QQRobot\exception\ParamsWrongException;
use QQRobot\QQRobotConst;
use QQRobot\ResolutionObserver;
use Throwable;

class TextChat  implements ResolutionObserver{

    private $baseApi='https://api.ai.qq.com/fcgi-bin/nlp/nlp_textchat';

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
            $appkey = $this->aiApiKey;
            $params = array(
                'app_id'     => $this->aiApiID,
                'time_stamp' => time(),
                'nonce_str'  => randStr(10),
                'session'       => mt_rand(1,100000),
                'question'       => $event->message,
            );
            $params['sign'] = getReqSign($params, $appkey);

            // 执行API调用
            $res=json_decode(doHttpPost($this->baseApi, $params));

            //commonLog('',json_encode($res));

            switch($res->ret){
                case 0:
                    $msg=$res->data->answer;
                    break;
                default:
                    $msg='有点深奥,我要好好想想,你稍后再来吧QAQ';
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

        return null;

    }


}