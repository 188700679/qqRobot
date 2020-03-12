<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 11:38
 * Ps:
 */

namespace QQRobot\components;


use QQRobot\ResolutionObserver;

class Interceptor implements ResolutionObserver{

    public function init($event,$sender) {
        if(isset($event->group_id)  && $event->group_id == '805348195'){
            exit;
        }
    }

}