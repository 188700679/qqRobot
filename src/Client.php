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
     * Processing plant path
     */
    const SERVER_NAME='QQRobot\\messageFactory\\';
    /**
     * @var array
     * What can be received for client
     */
    public $sendType=[];
    /**
     * @var bool
     * current server proxy this client?
     */
    protected $proxy=true;
    /**
     * @var array
     *   Current client type
     */
    private $clientObj=[];
    /**
     * @var Current client type properties
     */
    private $type;
    /**
     * @var null current server properties
     */
    private $server;
    /**
     * @var string Current send type
     * He can handle audio and video and message,you also can only send message or Insert inside @qq and emoji
     * but he only can unsolicited, he does rely on a current server example
     */

    private $msgType='msg';
    /**
     * @var string
     * Response to the producer of the event
     * its to respond to the current event, you must rely on the current server instance,
     * it looks like 'msg',but their application scenarios are different
     */
    private $backType='back';
    /**
     * @var string
     * Dedicated to audio and video processing,
     * But you have to make sure that the file path exists
     */
    private $videoType='rec';
    /**
     * @var
     * form the Closure
     * its Used to receive parameters in anonymous functions
     */
    private $args;
    /**
     * @var array
     * current client send formation,
     */
    private $_obs=[];
    /**
     * @var array
     * Hook function,
     * you can also use custom categories to create your own functions or class on your content
     */
    private $hook=[];

    /**
     * Client constructor.
     * @param null $server
     * He's used to judge the client Did he rely on current server example
     * If he doesn't rely on the current server example, He'll turn it off listenqqrobot for the prototypes
     */
    public function __construct($server=null){
        if(!$server){
            $this->server=new Server;
            $this->server->listenQQRobot=false;
        }else{
            $this->server=$server;
        }


        $this->sendType=[$this->videoType,$this->imgType,$this->msgType,$this->backType];

    }

    /**
     * @param string $str
     * @param Closure $closure
     * user:木鱼  2020/3/7 10:28
     * Entrance method
     * params 1 must be string,He can receive ['msg','back','ret']
     * Automatically bind corresponding methods
     * params 2 must be instance of Closure
     */
    public function on(string $str,Closure $closure):void{

        if(!in_array($str,$this->sendType)){
            throw new ParamsWrongException($str);
        }

        $this->type=$str;
        $this->$str=Closure::bind($closure,null,$this)();

    }

    /**
     * @return null
     * user:木鱼  2020/3/7 10:33
     */

    public function isListen(){
        return $this->server;
    }

    public function receive(){}


    /**
     * @param $method
     * @param null $callback
     * user:木鱼  2020/3/7 10:34
     */
    public function hook($method,$callback=null){

        if(is_array($method)){
            $this->hook=array_merge($this->hook,$method);
        }else{
            $this->hook[$method]=$callback;
        }


    }

    /**
     * user:木鱼  2020/3/7 10:34
     */
    public function parseIt(){
        $param=$this->clientObj[$this->type];
        $type=self::SERVER_NAME.ucfirst($this->type);
        if(count($param)==count($param,1)){
            $this->addObserver($type::typeHandle($param,$this->server));

        }else{
            foreach($param as $v){
                $this->addObserver($type::typeHandle($v,$this->server));
            }
        }

    }

    /**
     * @param $observer
     * user:木鱼  2019/12/30 16:41
     */
    public function addObserver($observer){
        $this->_obs[]=$observer;
    }

    /**
     * @return mixed
     * user:木鱼  2020/3/7 10:34
     */
    public function response(){
        return $this->server->response();
    }

    /**
     * @param $name
     * @param $value
     * user:木鱼  2020/3/7 10:34
     */
    public function __set($name,$value){
        $this->clientObj[$name]=$value;
        $this->$name($value);
    }

    /**
     * @param $name
     * @param $args
     * user:木鱼  2020/3/7 10:34
     */
    public function __call($name,$args){

        $this->server->args=(object)$args;

        $this->msgParse();
        call_user_func([new self,'send'],$this->_obs);
    }

    /**
     * @param $args
     * @return bool
     * user:木鱼  2020/3/7 10:34
     */
    public function messageSender($args){
        if(!empty($args)){
            if($host=$this->server->host){
                $host=$this->server->config->host;
            }
            $msgSender=new MessageSender($host);
            if(!$this->proxy){
                return false;
            }
            foreach($args as $v){
                $msgSender->sendOn($v);
            }
        }

        return false;
    }

    /**
     * @return array
     * user:木鱼  2020/3/7 10:34
     */
    protected function sendMsg(){
        return $this->_obs;
    }


}
