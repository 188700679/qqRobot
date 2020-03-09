<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 6:52
 * Ps:
 */

namespace QQRobot\decouplesSDK;


class RefinedAbstraction extends Abstraction{
    public function __construct(Implementor $imp){
        $this->imp=$imp;
    }

    public function operation(){
        return $this->imp->operationImp();
    }


}