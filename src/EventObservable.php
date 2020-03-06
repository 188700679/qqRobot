<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 8:53
 * Ps:
 */

namespace QQRobot;


interface EventObservable{

    public function addObserver($observer);
}