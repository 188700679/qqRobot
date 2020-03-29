<?php
/**
 * User: 木鱼
 * Date: 2020/3/4 6:57
 * Ps:
 */

namespace QQRobot;


use Closure;
use QQRobot\decouplesSDK\SdkRoute;
use QQRobot\exception\ParamsWrongException;
use QQRobot\lib\MessageSender;

class Client extends ClientDefinition{

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
     * @var Server
     * current server example
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
     * @var string
     * Get group information,its must be need group_id,he also rely on current server
     */
    private $groupinfoType='groupinfo';

    /**
     * @var
     * You are using a temporary variable to hold the result of an expression
     */
    private $args;

    /**
     * @var array
     * What can be received for client
     */
    public $sendType=[];


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
     * Processing plant path
     */
    const SERVER_NAME='QQRobot\\messageFactory\\';


    /**
     * Client constructor.
     * @param null $server
     * He's used to judge the client Did he rely on current server example
     * If he doesn't rely on the current server example, He'll turn it off listenqqrobot for the prototypes
     *
     *
     * You can pass in
     * null
     * array for config
     * server object
     *
     */
    public function __construct($server=null){
        if(!$server){
            $this->server=new Server;
            $this->server->listenQQRobot=false;
        }else{

            $this->paramsParse($server);
        }

        $this->sendType=[$this->videoType,$this->msgType,$this->backType,$this->groupinfoType];
        //$this->returnType=[$this->groupinfoType];

    }

    /**
     * @param $param|mixed
     * user:木鱼  2020/3/8 12:22
     * Parsing incoming parameters
     */
    private function paramsParse($param){
        if(is_array($param)){
            $this->server=new Server;
            $this->paramsToObj($param);
        }else{
            $this->server=$param;
        }

    }


    /**
     * @param $param
     * user:木鱼  2020/3/8 12:24
     * It depends on function paramsParse()
     */
    private function paramsToObj($param){
        foreach($param as $k=>$v){
            $this->server->$k=$v;
        }
    }


    /**
     * @param string $str
     * @param Closure $closure
     * @return mixed
     * user:木鱼  2020/3/8 12:24
     *
     * Monitoring and callback,
     * He is responsible for initiating the request and returning the parameters
     *
     * params 1 must be string,He can receive ['msg','back','ret','groupinfo']
     * Automatically bind corresponding methods
     * params 2 must be instance of Closure
     */
    public function on(string  $str,Closure $closure) {

        if(!in_array($str,$this->sendType)){
            throw new ParamsWrongException($str);
        }

        $this->server->listen=$str;
        $this->$str=Closure::bind($closure,null,$this)();

        return $this->$str();

    }

    /**
     * @param $observer
     * user:木鱼  2019/12/30 16:41
     * Store parsed parameters
     */
    public function addObserver($observer)
    {
        if($observer)$this->_obs[]=$observer;
    }

    /**
     * @return Server
     * user:木鱼  2020/3/8 12:27
     * Listen to the current service instance
     */
    public function isListen(){
        return $this->server;
    }

    public function receive(){}

    /**
     * @param $name
     * @param $args
     * @return mixed
     * user:木鱼  2020/3/8 12:28
     */
    public function __call($name,$args){
        $this->server->args=(object)$args;
        $this->msgParse();

        return call_user_func([new self,'send'],$this->_obs,$this->server);

    }

    /**
     * @param $method
     * @param null $callback
     * user:木鱼  2020/3/8 12:29
     *
     * Hook function, user-defined implementation
     */
    public function hook($method, $callback = null)
    {

        if (is_array($method)) {
            $this->hook = array_merge($this->hook, $method);
        } else {
            $this->hook[$method] = $callback;
        }


    }

    /**
     * user:木鱼  2020/3/8 12:30
     */
    public function parseIt(){

        $param=$this->clientObj[$this->server->listen];

        $type=self::SERVER_NAME.ucfirst($this->server->listen);

        if(!$this->server->host){
            $this->server->host=$this->server->config->host;
        }

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
    }


    public function messageSender($args,$server){
        if(empty($args)){
            return null;
        }
        foreach($args as $v){
            $tempArr=(new SdkRoute())->getAndReturn($server->listen,$server,$v);
        }
        return $tempArr;

    }






}
