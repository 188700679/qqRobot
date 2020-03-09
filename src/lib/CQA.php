<?php
/**
 * User: 木鱼
 * Date: 2019/12/30 0:29
 * Ps:
 */

namespace QQRobot\lib;



class CQA implements Definition{

    private $host;
    private $token;

    public function __construct(string $host = '127.0.0.1:5700',string $token = ''){
        $this->host = $host;
        $this->token = $token;
    }

    public function sendPrivateMsg($user_id, $message, $auto_escape = false){
        $api = Definition::send_private_msg;
        $param = [
            'user_id' => $user_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendPrivateMsgAsync($user_id, $message, $auto_escape = true){
        $api = Definition::send_private_msg_async;
        $param = [
            'user_id' => $user_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendGroupMsg($group_id, $message, $auto_escape = false){
        $api = Definition::send_group_msg."_rate_limited";
        $param = [
            'group_id' => $group_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function getGroupInfo($group_id){
        $api = Definition::get_group_info;
        $param = [
            'group_id' => $group_id,
        ];
        return $this->query($api, $param);
    }

    public function sendGroupMsgAsync($group_id, $message, $auto_escape = false){

        $api = Definition::send_group_msg_async."_rate_limited";
        $param = [
            'group_id' => $group_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    private function query($api, $param){
        $queryStr = '?';
        $param['access_token'] = $this->token; //追加access_token到参数表
        foreach($param as $key => $value){
            $queryStr.= ($key.'='.urlencode(is_bool($value)?((int)$value):$value).'&');
        }

        //halt('http://'.$this->host.$api.$queryStr);
        $result = json_decode(file_get_contents('http://'.$this->host.$api.$queryStr));


        switch($result->retcode){
            case 0:
                return $result->data;
            case 1:
                return NULL;
            default:
                throw new \Exception("Query Failed", $result->retcode);
        }
    }
}