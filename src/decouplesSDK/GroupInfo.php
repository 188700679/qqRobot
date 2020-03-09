<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 6:50
 * Ps:
 */

namespace QQRobot\decouplesSDK;



use QQRobot\lib\CQA;

class GroupInfo extends Implementor{ // 具体化角色A

    public $cq;

    public $args;



    public function __construct($host,$args){
        $this->cq=new CQA($host);
        $this->args=$args;
    }

    public function operationImp(){

        return $this->cq->getGroupInfo($this->args);


    }


}