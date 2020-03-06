<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 5:58
 * Ps:
 */

namespace QQRobot;


interface EventInterface{

    const VERSION='1.0';

    public function receive();


    public  function parseIt();

    public function response();
}