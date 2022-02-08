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

 
class GDrive
{

    /**
     * Proxy IP
     * @since 1.5
     **/
    protected $proxy;

    /**
     * Google drive auth user
     * @since 1.5
     **/
    protected $authUser = false;

    /**
     * File ID
     * @since 1.3
     **/
    protected $id;

    /**
     * Source links
     * @since 1.3
     **/
    protected $source;

    /**
     * Cookies
     * @since 1.3
     **/
    protected $cookiz;

    /**
     * Num of reloads
     * @since 1.3
     **/
    protected $relrd = 0;

    protected $tbl = 'drive_auth';
    protected $error = '';
    protected $key = 'lolypop';
    protected $authId = '';
    protected $db , $config =  NULL;
    


    /**
     * Constructor: Set proxy ip and auth user
     * @since 1.5
     **/
    public function __construct($db, $config, $aID = '')
    {
        if ($p = Proxy::getOne())
        {
            $this->proxy = $p;
        }

        if(!empty($aID))
        {
            $this->authId = $aID;
        }

        $this->db = $db;
        $this->config = $config;
        $this->setAuth();
    }


    /**
     * Get video data from file id
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function get($id = '')
    {
        if ($p = Proxy::getOne())
        {
            $this->proxy = $p;
        }
        
        if (!empty($id)) $this->id = $id;
        return $this->getSources();

    }

    protected function issetToken()
    {
        return isset($this->authUser['access_token']) && !empty($this->authUser['access_token']);
    }


    /**
     * Get video sources
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function getSources($reloads = 0)
    {
        $url = "https://docs.google.com/get_video_info?docid=" . $this->id;

        $isAuth = false;
        $cookies = $sources = [];
        $title = '';

        if ($this->issetToken())
        {
            $token = json_decode($this->authUser['access_token'], true);
            $isAuth = true;
        }

        usleep(rand(900000, 1500000));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, Helper::getUserAgent());
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT . '/data/cookiz/gdrive~' . $this->key . '.txt');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        if ($isAuth)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: " . $token['token_type'] . ' ' . $token['access_token']
            ));
        }
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
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result) || $info["http_code"] != "200")
        {
            if ($info["http_code"] == "200")
            {

                $error = "cURL Error (" . curl_errno($ch) . "): " . (curl_error($ch) ? : "Unknown");
            }
            else
            {
                $error = "Error Occurred (" . $info["http_code"] . ")";
            }
        }
        else
        {

            $header = substr($result, 0, $info["header_size"]);
            $result = substr($result, $info["header_size"]);
            preg_match_all("/^Set-Cookie:\\s*([^=]+)=([^;]+)/mi", $header, $cookie);
            foreach ($cookie[1] as $i => $val)
            {
                $cookies[] = $val . "=" . trim($cookie[2][$i], " \n\r\t");
            }

            parse_str($result, $fileData);

            if ($fileData['status'] == 'ok')
            {
                $streams = explode(',', $fileData['fmt_stream_map']);
                foreach ($streams as $stream)
                {
                    list($quality, $link) = explode("|", $stream);
                    $fmt_list = array(
                        '37' => "1080",
                        '22' => "720",
                        '59' => "480",
                        '18' => "360",
                    );
                    if (array_key_exists($quality, $fmt_list))
                    {
                        $quality = $fmt_list[$quality];
                        $sources[$quality] = ['file' => $link, 'quality' => $quality, 'type' => 'video/mp4', 'size' => 0];
                    }

                }
                if (isset($fileData['title']))
                {
                    $title = $fileData['title'];
                }

            }
            else
            {
                if(strpos($fileData['reason'], 'playbacks has been exceeded') !== false)
                {
                    if($this->getDL())
                    {
                        return true;
                    }
                    else
                    {
                        $error = 'This Video is unavailable !';
                    }
                }
                else
                {
                    $error = $fileData['reason'];
                }

                
                // $error = 'This Video is unavailable !';
            }

        }

        if (empty($error))
        {

            $this->saveToCache($sources);
            return ['title' => $title, 'data' => ['sources' => $sources, 'cookies' => $cookies]];
        }
        else
        {

            if ($reloads < 1)
            {
                return $this->getSources($reloads + 1);
            }

            $this->error = $error;
        }

        return false;

    }

    /**
     * Save data to cache
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function saveToCache($s)
    {
        $cache = new Cache($this->key);
        $cache->save($s);
    }


    /**
     * Get direct download link
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    public function getDL($id = '')
    {
        if(!empty($id)) $this->id = $id;

        return $this->isExist() ? $this->i() : false;

    }


    /**
     * Check DL exist or not
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    public function isExist()
    {
        if(Helper::isI($this->i()) == 200)
        {
            return true;
        }
        return false;
    }


    /**
     * Set cache key
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function setKey($key)
    {
        $this->key = $key;
    }


    /**
     * Get error
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Check errors
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function hasError()
    {
        if (!empty($this->error))
        {
            return true;
        }
        return false;
    }


    /**
     * Set auth user
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function setAuth()
    {

        $this->setAccount();
        if ($this->authUser)
        {
            if (!$this->isTokenValid())
            {
                if ($this->relrd < 2)
                {
                    $this->reloadToken();
                }

            }
            else
            {
                if($this->isBroken() && $this->isAFG())
                {
                    $this->broken(false);
                }
            }
        }

    }


    /**
     * Check auth token validation
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function isTokenValid()
    {
        if (!empty($this->authUser['access_token']))
        {
            $lastUpdated = $this->authUser['updated_at'];
            $timeFirst = strtotime($lastUpdated);
            $timeSecond = strtotime(Helper::tnow());
            $differenceInSeconds = $timeSecond - $timeFirst;

            if ($differenceInSeconds < 3500 && $differenceInSeconds > 1)
            {
                return true;
            }

        }
        return false;

    }


     /**
     * Update auth token
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function updateToken($token)
    {
        $this->db->where('id', $this->authUser['id']);
        $this->db->update($this->tbl, ['access_token' => $token, 'updated_at' => Helper::tnow() ]);
    }


    /**
     * Reload token
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function reloadToken()
    {
        $userData = [ 
            'client_id' => $this->authUser['client_id'], 
            'client_secret' => $this->authUser['client_secret'], 
            'refresh_token' => $this->authUser['refresh_token'], 
            'grant_type' => 'refresh_token'
        ];

        session_write_close();
        usleep(rand(1000000, 1500000));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.googleapis.com/oauth2/v4/token',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($userData) ,
            CURLOPT_USERAGENT => Helper::getUserAgent() ,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!$err)
        {
            $result = json_decode($response, true);
            if (!isset($result['error']))
            {
                $tokenInfo = $result;
            }
            else
            {
                $this->error = 'gdrive_access_token ' . $result['error'] . ' => ' . $result['error_description'];
            }
        }
        else
        {
            $this->error = 'gdrive_access_token ' . $status . ' => ' . $err;
        }

        if (isset($tokenInfo))
        {
            if (isset($tokenInfo['expires_in'])) unset($tokenInfo['expires_in']);
            if (isset($tokenInfo['scope'])) unset($tokenInfo['scope']);

            $tokenInfo = json_encode($tokenInfo);

            $this->updateToken($tokenInfo);
            $this->authUser['access_token'] = $tokenInfo;
            if($this->isBroken() && $this->isAFG())
            {
                $this->broken(false);
            }

        }
        else
        {
            $this->broken();
            if(!$this->isAFG())
            {
                $this->relrd += 1;
                $this->setAuth();
            }

        }

    }


    /**
     * Get DL API
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function i()
    {
        return 'https://www.googleapis.com/drive/v2/files/'.$this->id.'?key='.$this->getAK().'&alt=media';
    }


    /**
     * Update broken links
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function broken($a = true)
    {
        if($a)
        {
            $this->updateToken('');
            $this->authUser['access_token'] = '';
            $st = 1;
        }
        else
        {
            $st = 0;
        }

        $this->db->where('id', $this->authUser['id']);
        $this->db->update($this->tbl, ['status' => $st]);
    }

    protected function isBroken()
    {
        return isset($this->authUser['status']) && $this->authUser['status'] == 1;

    }


    /**
     * Check and set auth user accounts
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function setAccount()
    {

        if(!$this->isAFG())
        {
            $this->db->where('status', 0);
            $users = $this->db->get($this->tbl);
            if ($this->db->count > 0)
            {
    
                $this->authUser = $users[array_rand($users) ];
            }
        }
        else
        {
            $this->db->where('id', $this->authId);
            $user = $this->db->getOne($this->tbl);
            if ($this->db->count > 0)
            {
                $this->authUser = $user;
            }
        }


    }

    protected function isAFG()
    {
        return !empty($this->authId) ? true : false;
    }



    /**
     * Get API key
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function getAK()
    {
        $ak = 'AIzaSyD43F1N3Wvj2vfqpgyImQgv81eQylP-bJk';
        if(!empty($this->authUser['apikey']))
        {
            $ak = $this->authUser['apikey'];
        }
        return $ak;
    }  

    public function isValidAuth()
    {
        return $this->issetToken();
    }

    

}

