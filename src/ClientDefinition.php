<?php
/**
 * User: 木鱼
 * Date: 2020/3/2 7:55
 * Ps:
 */

namespace QQRobot;


abstract class ClientDefinition implements SendInterface,EventInterface{




    /**
     * @param $args
     * @return mixed
     * user:木鱼  2020/3/7 10:37
     *
     * Calling basic methods to assemble top level logic
     */
    public function send($args) {

        return $this->messageSender($args);
    }

    /**
     * @return mixed
     * user:木鱼  2020/3/7 10:38
     * Parsing all incoming parameters,He relies on client example
     */
    public function msgParse(){
        return $this->parseIt();
    }

    /**
     * @param $args
     * @return mixed
     * user:木鱼  2020/3/7 10:45
     */
    abstract protected function messageSender($args);


    /**
     * @return mixed
     * user:木鱼  2020/3/7 10:46
     */
    abstract public function parseIt();



}