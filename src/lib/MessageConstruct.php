<?php
/**
 * User: 木鱼
 * Date: 2019/12/29 23:12
 * Ps:
 */

namespace QQRobot\lib;


class MessageConstruct{


    public $msg;
    public $id;
    public $toGroup;
    public $async;

    /**
     * @param string $msg 消息内容
     * @param string|int $id QQ(群)号
     * @param bool $toGroup true->发到群里(默认值), false->发私聊
     * @param bool $async 是否异步发送（默认异步）
     */
    public function __construct(string $msg, $id, bool $toGroup = true, bool $async = true){

        $this->msg = $msg;
        $this->id = $id;
        $this->toGroup = $toGroup;
        $this->async = $async;
    }
}