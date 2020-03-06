<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 6:04
 * Ps:
 */

namespace QQRobot;


use QQRobot\exception\BadResponseException;

abstract class ServerDefinition implements EventInterface{

    /**
     * @var 响应
     */
    public $accept;

    public $listen;


    /**
     * @return mixed
     * user:木鱼  2019/12/25 2:20
     */
    public function data(){
        return $this->dataHandle();
    }


    /**
     * @return mixed
     * user:木鱼  2019/12/25 2:20
     *
     */
    public function rMessage(){
        return $this->returnMessage();
    }


    /**
     * @return mixed
     * user:木鱼  2019/12/25 2:20
     */
    public  function response(){
        return $this->accept=$this->accept();

    }




    public function isListen(){
        return $this->listen;
    }


    abstract protected function logOutput();


    /**
     * @return mixed
     * user:木鱼  2019/12/22 6:01
     * 数据处理
     */
    abstract protected function dataHandle();


    /**
     * @return mixed
     * user:木鱼  2019/12/22 5:59
     * 监听代理
     */
    abstract protected function setListen();


    /**
     * @return mixed
     * user:木鱼  2019/12/22 5:59
     * 获取参数
     */
    abstract protected function accept();

    /**
     * @return mixed
     * user:木鱼  2019/12/22 6:02
     * 数据返回
     */
    abstract protected function returnMessage();
}