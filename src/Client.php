<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 6:57
 * Ps:
 */

namespace QQRobot;


use Closure;
use QQRobot\exception\ParamsWrongException;
use QQRobot\lib\MessageSender;

class Client extends ClientDefinition{

    /**
     * * @property $receive
     */
    const SERVER_NAME='QQRobot\\messageFactory\\';
    private $clientObj=[];
    private $type;
    private $server;
    private $msgType='msg';
    private $backType='back';
    private $videoType='rec';
    private $args;
    public $sendType=[];
    private $_obs=[];
    private $hook=[];

    protected $proxy=true;

    public function __construct($server=null){
        if(!$server){
            $this->server=new Server;
            $this->server->listenQQRobot=false;
        }else{
            $this->server=$server;
        }


        $this->sendType=[$this->videoType,$this->imgType,$this->msgType,$this->backType];

    }
    public function on(string  $str,Closure $closure):void {

        if(!in_array($str,$this->sendType)){
            throw new ParamsWrongException($str);
        }

        $this->type=$str;
        $this->$str=Closure::bind($closure,null,$this)();

    }

    /**
     * @param $observer
     * user:木鱼  2019/12/30 16:41
     */
    public function addObserver($observer)
    {
        $this->_obs[]=$observer;
    }

    public function isListen(){
        return $this->server;
    }

    public function receive(){}

    public function hook($method, $callback = null)
    {

        if (is_array($method)) {
            $this->hook = array_merge($this->hook, $method);
        } else {
            $this->hook[$method] = $callback;
        }


    }

    public function parseIt(){
        $param=$this->clientObj[$this->type];
        $type=self::SERVER_NAME.ucfirst($this->type);
        if(count($param) ==count($param,1)){
            $this->addObserver($type::typeHandle($param,$this->server));

        }else{
            foreach($param as $v){
                $this->addObserver($type::typeHandle($v,$this->server));
            }
        }

    }


    protected function sendMsg(){
        return $this->_obs;
    }


    public function response(){
        return $this->server->response();
    }



    public function __set($name,$value){
        $this->clientObj[$name]=$value;
        $this->$name($value);
    }

    public function __call($name,$args){

        $this->server->args=(object)$args;

        $this->msgParse();
        call_user_func([new self,'send'],$this->_obs);
    }



    public function messageSender($args){
        if(!empty($args))
        {
            $msgSender=new MessageSender($this->server->config->host);
            if(!$this->proxy)
            {
                return false;
            }
            foreach($args as $v)
            {
                $msgSender->sendOn($v);
            }
        }

        return false;
    }






}
