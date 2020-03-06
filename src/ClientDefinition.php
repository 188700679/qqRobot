<?php
/**
 * User: 木鱼
 * Date: 2020/3/2 7:55
 * Ps:
 */

namespace QQRobot;


abstract class ClientDefinition implements SendInterface,EventInterface{

    public $accept;



    public $receive;




    public function send($args) { // 调用基本方法组装顶层逻辑

        return $this->messageSender($args);
    }


    public function msgParse(){
        return $this->parseIt();
    }

    abstract protected function messageSender($args);

    abstract public function parseIt();



}