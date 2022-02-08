<?php

/**
 * ====================================================================================
 *                           Google Drive Proxy Player (c) CodySeller
 * ----------------------------------------------------------------------------------
 * @copyright This software is exclusively sold at codester.com. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in an illegal activity. You must delete this software immediately or buy a proper
 *  license from https://www.codester.com/codyseller?ref=codyseller.
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author CodySeller (http://codyseller.com)
 * @link http://codyseller.com
 * @license http://codyseller.com/license
 */


class Proxy
{
    
    protected $proxyList = [];
    protected $proxyFile;
    protected $error = '';
    protected $username = '';
    protected $password = '';



    public function __construct($username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->proxyFile =  'data/proxy/proxy.txt';
        $this->loadProxy();
    }

    public static function set($meta, $value) {
        if (!empty($value)) {
            self::$$meta = $value;
        }
    }



    public function refresh(){
        if(!$this->hasError())
        {
            $proxies = $this->getProxyList();
            if(!empty($proxies))
            {
                $broken = [];
                $active = [];
    
                foreach($proxies as $proxy)
                {
                    if($this->check($proxy))
                    {
                        $active[] = $proxy;
                    }
                    else
                    {
                        $broken[] = $proxy;
                    }
                }
                $this->saveProxy($active);
                $this->saveBrokenProxy($broken);
                
            }

        }
    }

    public function saveBrokenProxy($proxy, $t = 'default')
    {
        $this->toBroken();
        $r = false;
        if($t == 'default')
        {
            $this->addBroken($proxy);
        }
        else
        {
            $r = $this->saveProxy($proxy);
        }

        
        $this->toActive();
        return $r;
    }

    protected function toBroken()
    {
        $this->proxyFile =  'data/proxy/broken_proxy.txt';
    }

    protected function toActive()
    {
        $this->proxyFile =  'data/proxy/proxy.txt';
    }

    public function check($proxy)
    {
        if(in_array($proxy, $this->proxyList))
        {
            if(!$this->c($proxy))
            {
                $pkey = array_search($proxy, $this->proxyList);
                if($pkey !== false)
                {
                    unset($this->proxyList[$pkey]);
                    $this->saveProxy($this->proxyList);
                    $this->saveBrokenProxy($proxy);
                }
            }else{
                return true;
            }
        }
        return false;
    }

    protected function c($proxy)
    {
        usleep(rand(900000, 1500000));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://docs.google.com/get_video_info?docid=1uDTqyE_1OIBc6HLHBPyJpEA7UJiaZrVE');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->username.':'.$this->password);
        if(self::isSocks($proxy))
        {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
        curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       
        if($status_code == '200'){
            return true;
        }else{
            return false;
        }



    }

    public static function isSocks($p)
    {
        $httpPorts = ['1080','1081'];
        if($port = self::getPort($p)){
            if(in_array($port, $httpPorts))
            {
                return true;
            }
        }
        return false;
    }

    public static function isHttp($p)
    {
        $httpPorts = ['80','8080','3128'];
        if($port = self::getPort($p)){
            if(in_array($port, $httpPorts))
            {
                return true;
            }
        }
        return false;
    }

    public static function getPort($p)
    {
        if(strpos($p, ':') !== false)
        {
            $pArr = explode(':', $p);
            return $pArr[1];
        }
        return false;
    }

    protected function addBroken($p)
    {
        if(is_writable($this->proxyFile)){
            $brokenProxyFile = fopen(ROOT.'/'.$this->proxyFile, "a") or die("Unable to open broken proxy file!");
            $txt = '';
            if(is_array($p))
            {
                foreach($p as $v)
                {
                    $txt .= trim($v).",\n";
                }
            }else{
                $txt = trim($p).",\n";
            }
            
            fwrite($brokenProxyFile, $txt);
            fclose($brokenProxyFile);
        }
    }
 
    public function saveProxy($plist = [])
    {
        if(!$this->hasError())
        {
            if(is_array($plist))
            {
                if(is_writable(ROOT.'/'.$this->proxyFile))
                {
                    $proxyFile = fopen(ROOT.'/'.$this->proxyFile, "w") or die("Unable to open proxy file!");
                    $prxy = '';
                    foreach($plist as $p)
                    {
                       
                        if(!empty($p) && strpos($p, ':') !== false)
                        {
                            $prxy .= trim($p).",\n";
                        }
                        
                    }
                    fwrite($proxyFile, $prxy);
                    fclose($proxyFile);
                    return true;
                }
                else
                {
                    $this->error = 'Proxy file is not writable ! -> <b>' . $this->proxyFile . '</b>';
                }
            }
            else
            {
                $this->error = 'Invalid proxy list formated provided !';
            }
        }
        return false;
        
    }

    




    public function getProxyList($t = 'active')
    {
        if($t == 'broken')
        {
            $this->toBroken();
            $p = $this->loadProxy('broken');
            $this->toActive();
            if(!$this->hasError())
            {   
                return $p;
            }
        }
        else
        {
            if(!$this->hasError())
            {   
                return $this->proxyList;
            }
        }

        return false;

    }

    public static function getOne()
    {
        $obj = new self;

        if(!$obj->hasError())
        {   
            if(!empty($obj->proxyList))
            {
                return trim($obj->proxyList[array_rand($obj->proxyList)]);
            }
            
        }
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function hasError()
    {
        if(!empty($this->error))
        {
            return true;
        }
        return false;
    }

    protected function loadProxy($t = 'active')
    {
       
        if(file_exists(ROOT.'/'.$this->proxyFile))
        {
            if(is_readable(ROOT.'/'.$this->proxyFile))
            {
                $proxyContent = @file_get_contents(ROOT . '/' . $this->proxyFile);
                if(!empty($proxyContent))
                {
                    $proxyArr = explode(',', rtrim($proxyContent, ','));
                    foreach($proxyArr as $k => $v)
                    {
                        $v = trim($v);
                        if(!empty($v)) 
                        {
                            $proxyArr[$k] = $v;
                        }
                        else
                        {
                            unset($proxyArr[$k]);
                        }
                       
                    }
                    
                    if($t == 'active')
                    {
                        $this->proxyList = $proxyArr;
                    }
                    else
                    {
                        return $proxyArr;
                    }

                    
                }
            }
            else
            {
                $this->error = 'Proxy file is not readable ! -> <b>' . $this->proxyFile . '</b>';
            }
        }
        else
        {
            $this->error = 'Proxy file does not exist ! -> <b>' . $this->proxyFile . '</b>';
        }
    }

    public function clear($t = 'active')
    {
        if($t == 'broken')
        {
            $this->toBroken();
            file_put_contents(ROOT . '/' . $this->proxyFile, "");
            $this->toActive();
        }
        else
        {
            file_put_contents(ROOT . '/' . $this->proxyFile, "");

        }
    }


}