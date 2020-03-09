<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 6:53
 * Ps:
 */

namespace QQRobot\decouplesSDK;


class SdkRoute{


    public function getAndReturn($type,$server,$args){

        switch($type){
            case 'groupinfo':
                $abstraction = new GroupInfo($server->host,$args);
                break;
            case 'back':
                $abstraction = new FromBack($server,$args);
                break;
            case 'msg':
                $abstraction = new MsgActive($server,$args);
                break;
            default :
                return 'error  request!';
                break;
        }


         $abstraction =new RefinedAbstraction($abstraction);
        return $abstraction->operation();
    }
}