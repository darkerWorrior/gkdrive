<?php





class OneDrive 
{

    protected $url;
    protected $error;

    public function __construct()
    {
        
    }

    public function get($url)
    {
        $err = 0;
    

        if (strpos($url, 'my.sharepoint.com') !== false) {
            $url = $url . '&download=1';
            $ls = Helper::isI($url);
          
            if($ls == 404)
            { 
                $err = 1;
            }
           
        } else {
            if (filter_var($url, FILTER_VALIDATE_URL) !== FALSE && strpos($url, "1drv.ms") !== false) {
                $url = strtok($url, "?");
                $url = @file_get_contents(str_replace('?txt', '', str_replace('1drv.ms', '1drv.ws', $url)) . '?txt');
                if(strpos($url, 'login.live.com') !== false)
                {
                    $err = 1;
                }
            }
            else
            {
                $err = 1;
            }
        }
        

        if(empty($err))
        {
           
            $link =  PROOT . '/stream/360/' . base64_encode(urlencode($url)) . '/__003?vh='.time();
            $resp = [
                [
                    'file' => $link,
                    'q' => 360
                ]
            ];
         
            return $resp;
        }
        else
        {
            return false;
        }
    }



}