<?php
/**
 * User: 木鱼
 * Date: 2019/12/30 0:12
 * Ps:
 */

namespace QQRobot\lib;



class MessageSender{

    public $cq;

    public  function __construct($host){
        $this->cq=new CQA($host);
    }


    /**
     * @param  $message
     */
    public function sendOn($message){
        if($message->toGroup){
            if($message->async){
                $this->cq->sendGroupMsgAsync($message->id, $message->msg);
            }else{
                $this->cq->sendGroupMsg($message->id, $message->msg);
            }
        }else{
            if(isset($message->async) && $message->async){

                $this->cq->sendPrivateMsgAsync($message->id, $message->msg);
            }else{

                $this->cq->sendPrivateMsg($message->id, $message->msg);
            }
        }
    }
}