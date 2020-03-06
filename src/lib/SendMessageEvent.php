<?php
/**
 * User: 木鱼
 * Date: 2019/12/29 23:09
 * Ps:
 */

namespace QQRobot\lib;



class SendMessageEvent{


    /**
     * 消息从哪来发到哪
     * @param string $msg 消息内容
     * @param object $server 服务实例
     * @return Message
     */
    public static function sendBack(string $msg,object $server):MessageConstruct{

        return new MessageConstruct($msg,isset($server->group_id)?$server->group_id:
            $server->user_id,isset($server->group_id));

    }
}