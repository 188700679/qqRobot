<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 6:51
 * Ps:
 */

namespace QQRobot\decouplesSDK;


class Abstraction{
    protected $imp;

    public function operation() {

        $this->imp->operationImp();
    }


}