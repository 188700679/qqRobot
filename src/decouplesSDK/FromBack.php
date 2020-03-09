<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 9:48
 * Ps:
 */

namespace QQRobot\decouplesSDK;


use QQRobot\lib\CQA;
use QQRobot\lib\MessageSender;
use QQRobot\lib\SendMessageEvent;

class FromBack extends Implementor{ // 具体化角色A



    private $args;

    private $server;

    public function __construct($server,$args){
        $this->server=$server;
        $this->args=$args;
    }

    public function operationImp(){
        $messageSender=new MessageSender($this->server->host);

        $message=SendMessageEvent::sendBack($this->args,$this->server);


        return $messageSender->sendOn($message);



    }


}