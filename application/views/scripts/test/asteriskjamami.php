<?php
use Zend\Log\Logger as Logger;
class testing 
{
    public $host ='10.254.236.2';
    public $baseUriPath = '/asterisk/mxml';
    public $username='CallStreamAMI';
    public $password='aab4acbfe6c0caeb4906bb5b7210d0fa';
    public $cookie;
    public $cookieStart; // start timestamp for active cookie
    public $autologin = true;
    // client
    public $client;
    public $logger;
    public $string;

    
public function show($a)
    {

    }
   protected function isCookieValid($cookie)
    {
        // no cookie
        if ($cookie === null) {
            return false;
        }

        // check cookie age
        $age = intval(substr($cookie, strpos($cookie, 'Max-Age=') + 8));
        return (($this->cookieStart + $age) > time());
    }

    
  public function login()
    {
        return $this->execute('login', array(), true);
        
        //$this->client = curl_init($url); 
        //$output = curl_exec($this->client);
        
    }
    
   
 public function setHost($host, $port = '8088')
    {
        $this->host = $host;
        $this->host = ($port != '') ? $this->host . ':' . $port : '';
        return $this;
       
        
    }
     public function execute($action, $parameters = array(), $includeCredentials = false)
    {
        // url;
        $url = $this->buildUrl($action, $parameters, $includeCredentials);
        
        // curl init
        if (!isset($this->client)) {
            $this->client = curl_init($url); 
            
        }

        // ping / autologin check
        if ($this->autologin && (!in_array($action, array('login')))) {
            if (!$this->isCookieValid($this->cookie)) {
               
                $this->login();
            }
        }

        // log
        $msg = __METHOD__ . ": action: '{$action}', parameters: '" . var_export($parameters, true) . "'";
 //       $this->logger->log(Logger::DEBUG, $msg);
   //     $this->logger->log(Logger::DEBUG, $url);

        // options
        curl_setopt($this->client, CURLOPT_URL, $url);
        curl_setopt($this->client, CURLOPT_HEADER, 1);
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);

        // include cookie if exists
        if (isset($this->cookie)) {
            curl_setopt($this->client, CURLOPT_COOKIE, $this->cookie);
        }

        // exec call
        $output = curl_exec($this->client);

        // process response
     //   $response = new \Common\Model\Business\Ami\AmiResponse();
       // $response->parseCurlResponse($output, $this->client);
        // set cookie
       // $this->cookieStart = time();
        //$this->cookie = $response->getCookie();

        // log
       // $msg = __METHOD__ . ": response: '" . var_export($response->getRawContent(), true) . "'";
        //$this->logger->log(Logger::DEBUG, $msg);

       // return $response;
    }

     protected function buildUrl($action, $parameters = array(), $includeCredentials = false)
    {
         //$action = "DBPut&family";
        $url = "http://{$this->host}{$this->baseUriPath}?";
        // credentials
        $url .= ($includeCredentials) ? "username={$this->username}&secret={$this->password}&" : '';
        // action
        $url .= 'action=' . $action;
        //$url = "http://10.254.236.2:8088/asterisk/mxml?username=CallStreamAMI&secret=aab4acbfe6c0caeb4906bb5b7210d0fa";
        
        //exit
        // parameters
      
       // $parameters = array ("ext7651","line_type","8");
      //print_r(array_values($parameters));
        foreach ($parameters as $key => $value) {
            $url .= "&" . rawurlencode($key) . "=" . rawurlencode($value);
         //    echo $url;
            //print_r(array_values($parameters));
        }
         return $url;            
          
    }

    
}