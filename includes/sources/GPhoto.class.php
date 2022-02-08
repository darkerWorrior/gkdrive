<?php






class GPhoto
{


    protected $url;
    protected $error;


    public function __construct()
    {
        
    }

    public function get($url)
    {
        $this->url = $url;
        return $this->getSources();
    }

    protected function getSources()
    {

        $error = 0;
        $content = Helper::curl($this->url);
        $checkLink = preg_match('/photos.google.com\/share\/.*\/photo\/.*/', $this->url, $match);
        if ($checkLink) 
        {
            $__decodedSource = rawurldecode($content);
            preg_match_all('/https:\/\/(.*?)=m(22|18|37)/', $__decodedSource, $matched);
            if(isset($matched[2]))
            {
                foreach ($matched[2] as $v) 
                {
                    switch ($v) 
                    {
                        case '18':
                            $__a[360] = [
                                'file' =>  'https://'.$matched[1][0] .'=m18',
                                'q' => '360'
                            ];
                        break;
                        case '22':
                            $__a[720] = [
                                'file' =>  'https://'.$matched[1][0] .'=m22',
                                'q' => '720'
                            ];
                        break;
                        case '37':
                            $__a[1080] = [
                                'file' =>  'https://'.$matched[1][0] .'=m37',
                                'q' => '1080'
                            ];
                        break;
                    }
                }
                krsort($__a);
                $res = implode(',', $__a);
                $sources = '[' . $res . ']';
                $isOx = preg_match('/\[\]/', $sources, $match);
                if ($isOx) 
                {
                    $error = 1;
                }
            }
            else
            {
                $error = 1;
            }

        } 
        else 
        {
            preg_match('/<meta property="og:image" content="(.*?)\=.*">/', $content, $matched);
            if(isset($matched[1]))
            {
                $q__360p = trim($matched[1] . '=m18');
                $q__720p = trim($matched[1] . '=m22');
                $q__1080p = trim($matched[1] . '=m37');
                if (Helper::isI($q__1080p) != 404) 
                {
                    $sources = [
                        [
                            'file' => Helper::getGPhotoURI($q__360p),
                            'q' => '360'
                        ],
                        [
                            'file' => Helper::getGPhotoURI($q__720p),
                            'q' => '720'
                        ],
                        [
                            'file' => Helper::getGPhotoURI($q__1080p),
                            'q' => '1080'
                        ]
                    ];
                } 
                else if (Helper::isI($q__720p) != 404) 
                {
                
                    $sources = [
                        [
                            'file' => Helper::getGPhotoURI($q__360p),
                            'q' => '360'
                        ],
                        [
                            'file' => Helper::getGPhotoURI($q__720p),
                            'q' => '720'
                        ]

                    ];
                
                } 
                else if (Helper::isI($q__360p) != 404) 
                {
                    $sources = [
                        [
                            'file' => Helper::getGPhotoURI($q__360p),
                            'q' => '360'
                        ]
                    ];
                }
                 else 
                 {
                    $error = 1;
                 }
            }
            else 
            {
            $error = 1;
            }

        }

        if(empty($error))
        {
            $sources = $this->clean($sources);
            return $sources;
        }

        return false;

    }

    protected function clean($sources)
    {
        return json_decode(str_replace('lh3.googleusercontent.com', '3.bp.blogspot.com', json_encode($sources)), true);
    }

    public function getError()
    {
        return $this->error;
    }



}