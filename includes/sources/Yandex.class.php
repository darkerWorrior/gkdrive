<?php 




class Yandex 
{


    protected $url;
    protected $sources = '';
    protected $error;
    protected $config;
    protected $proxy = '';

    public function __construct($config)
    {
        $this->config = $config;
        if ($p = Proxy::getOne())
        {
            $this->proxy = $p;
        }

    }

    public function get($url)
    {
        $this->url = $url;
        $this->loadSources();
        return $this->getAdapURI();
    }

    protected function loadSources()
    {

    
        $page = $this->getPage();

        if(!empty($page))
        {
            $dom = new DomDocument();
            libxml_use_internal_errors(true);
            @$dom->loadHTML($page);
            libxml_clear_errors();
            $xPath = new DOMXPath($dom);
        
            $scripts = $xPath->query('//script[@type="application/json"]');
            
            $v = $scripts->item(0);
            $v = json_decode($v->textContent, true);
        
        
            if(isset($v['rootResourceId']))
            {
                $sources = $v['resources'][$v['rootResourceId']]['videoStreams']['videos'];
            }
            isset($sources) && !empty($sources) ? $this->sources = $sources : '';
        }
        
    

    }

    protected function getAdapURI()
    {
        if(!empty($this->sources))
        {
            $key    = array_search('adaptive', array_column($this->sources, 'dimension'));
            if(!empty($key))
            {
                return $this->sources[$key]['url'];
            }
        }
    }

    protected function getPage()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, Helper::getUserAgent() );
        curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT .'/data/cookiz/cookiz.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'host: yadi.sk',
            'origin: https://yadi.sk',
        ));
        if (!empty($this->proxy))
        {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->config['proxyUser'] . ':' . $this->config['proxyPass']);
            if (Proxy::isSocks($this->proxy))
            {
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            }
        }
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($status == 200)
        {
            return $result;
        }
        return '';
    }

}