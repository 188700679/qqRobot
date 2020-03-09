<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 8:10
 * Ps:
 */

namespace QQRobot;


interface SendInterface{

    public function send($args,$server);

    public function addObserver($observer);
}