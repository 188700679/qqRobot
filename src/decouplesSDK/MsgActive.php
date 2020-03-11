<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 12:32
 * Ps:
 */

namespace QQRobot\decouplesSDK;


use QQRobot\lib\MessageSender;

class MsgActive extends Implementor{ // 具体化角色A

    public $server;

    public $args;


    public function __construct($server,$args){

        $this->server=$server;
        $this->args=$args;
    }

    public function operationImp(){

        $messageSender=new MessageSender($this->server->host);

        return $messageSender->sendOn($this->args);


    }
}
