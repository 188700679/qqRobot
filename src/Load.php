<?php
/**
 * User: 木鱼
 * Date: 2020/3/6 14:18
 * Ps:
 */

namespace QQRobot;


use QQRobot\exception\ParamsWrongException;

class Load implements EventObservable{

    /**
     * @var array
     * 事件队列
     */
    private $_obs=[];



    private $event;

    public function __construct($event=null){

        if(is_array($event)){
            $this->event=(new Server($event))->response();
        }

        if($event==null){
            $this->event=(new Server())->response();
        }

        if(is_object($event)){
            $this->event=$event;
        }

    }

    public function loader()
    {

        foreach($this->_obs as $obs){
            $obs->init($this->event,$this);
        }


    }

    /**
     * @param $observer
     * user:木鱼  2019/12/30 16:41
     */
    public function addObserver($observer)
    {

        $this->_obs[]=$observer;
    }
}