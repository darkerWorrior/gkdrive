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


class App
{

    /**
     * Application Variables
     * @since 1.3
     *
     */
    protected $db;
    protected $config;
    protected $actions = ['dashboard', 'links', 'video', 'api', 'servers', 'ajax', 'bulk', 'ads', 'settings', 'profile', 'login', 'logout'];
    protected $action;
    protected $alerts = [];
    protected $data = [];
    protected $videoType;

    /**
     * User Variables
     * @since 1.3
     *
     */
    protected $logged = false;
    protected $hasAccess = false;
    protected $isAdmin = false;
    protected $userId = NULL;


    /**
     * Constructor: Checks logged user status
     * @since 1.3
     *
     */
    public function __construct($db, $config)
    {
        $this->db = $db;
        $this->config = $config;
    }


    /**
     * Run Applicatioin
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function run()
    {

        if (isset($_GET['a']) && !empty($_GET['a']))
        {
            $this->setup();

            $var = explode('/', $_GET['a']);
            $var[0] = str_replace('.', '', $var[0]);

            $this->action = Helper::clean($var[0]);
            unset($var[0]);

            $this->resolveCustomSlugs();

            if (in_array($this->action, $this->actions))
            {

                $this->check();
                if (method_exists($this, $this->action))
                {
                    return call_user_func_array([$this, $this->action], $var);
                }
                else
                {
                    //method does not exist
                    die('This method is does not exists in app !');
                }
            }
            else
            {
                //page not found
                $this->_404();
            }

        }

        return $this->home();

    }


    /**
     * Check user permission
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function check()
    {
        $public = ['login', 'video', 'api'];

        if (!$this->logged)
        {
            if (in_array($this->action, $public))
            {
                $this->hasAccess = true;
            }
            else
            {
                $this->_400();
            }
        }
        else
        {
            $this->hasAccess = true;
        }

    }


    /**
     * Setup application data
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function setup()
    {

        if (isset($_SESSION['alerts']))
        {
            $this->alerts = $_SESSION['alerts'];
            unset($_SESSION['alerts']);
        }

        if (isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
        {
            $user = new User($this->db);
            $user = $user->findByUsername($_SESSION['user']);
            if (!empty($user))
            {
                //we have only admin user
                $this->logged = true;
                $this->userImg = $user['img'];

            }
        }

    }


    /**
     * Resolve custom slugs issues
     * @author CodySeller <https://codyseller.com>
     * @since 1.4
     */
    protected function resolveCustomSlugs()
    {
        $videoSlug = $this->getSlug('playerSlug');
        $cslugs = [$videoSlug => 'video'];
        if (array_key_exists($this->action, $cslugs))
        {
            $this->action = $cslugs[$this->action];
        }

    }


    /**
     * Get custom slug
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function getSlug($slug)
    {
        $default = ['videoSlug' => 'video'];
        return !empty($this->config[$slug]) ? $this->config[$slug] : $default[$slug];
    }


    /**
     * Home page
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function home()
    {
        $this->display('home', true);
    }
    

    /**
     * Dashboard page
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function dashboard()
    {

        $this->setTitle('Dashboard | GDplyr Application');

        $this->analyze();
        $this->display('dashboard');
    }

    /**
     * Profile page
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function profile()
    {
        $this->setTitle('Admin profile | ' . APP_NAME . ' Application');
        $user = new User($this->db);
        $user->load($this->config['adminId']);

        if (Helper::isPost())
        {
            $username = Helper::getReqData('username');
            $password = Helper::getReqData('password');
            $confirmPassword = Helper::getReqData('confirm_passsword');
            $img = Helper::getReqData('image');

            if (empty($username))
            {
                $this->addAlert('Username is required !', 'danger');
            }
            else
            {
                if ($username != $user->obj['username'])
                {
                    $isNewU = true;
                }
            }

            if (!empty($password))
            {
                if (empty($confirmPassword))
                {
                    $this->addAlert('Confirm password is required !', 'danger');
                }
                else
                {
                    if ($password != $confirmPassword)
                    {
                        $this->addAlert('Password does not matched !', 'danger');
                    }
                }

            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
            {
                $piname = $_FILES['image']['name'];
                $pitmp = $_FILES['image']['tmp_name'];
                $imgDir = "/uploads/";
                if (!file_exists(ROOT . $imgDir))
                {
                    $this->addAlert("Profile image upload failed ! -> <b>{$imgDir}</b> folder does not exist . ", 'warning');
                }
                else
                {
                    if (!is_writable(ROOT . $imgDir))
                    {
                        $this->addAlert("Profile image upload failed ! -> <b>{$imgDir}</b> folder is not writable . ", 'warning');
                    }
                    else
                    {
                        $upname = Helper::uploadImg($piname, $pitmp, $imgDir);
                        if (!$upname)
                        {
                            $this->addAlert("Profile image upload failed. -> Invalid file format !", 'warning');
                        }
                        else
                        {
                            $img = $upname;
                        }
                    }
                }

            }

            if (!$this->hasAlerts())
            {

                $data = ['username' => $username, 'img' => $img];

                if (!empty($password))
                {
                    $hasedPass = password_hash($password, PASSWORD_DEFAULT);
                    $data['password'] = $hasedPass;
                }

                if ($user->assign($data)->save())
                {
                    if ($isNewU)
                    {
                        $_SESSION['user'] = $username;
                    }
                    $this->addAlert('Saved changes successfully !', 'success');
                    $this->saveAlerts();
                    Helper::redirect('profile');
                }

            }

        }

        $this->addData($user->obj, 'user');
        $this->display('profile');
    }

    /**
     * Login page
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function login()
    {

        if ($this->logged)
        {
            Helper::redirect('dashboard');
        }

        if (Helper::isPost())
        {
            $username = Helper::getReqData('username');
            $password = Helper::getReqData('password');

            $user = new User($this->db);
            $user = $user->findByUsername($username);

            if (!empty($user))
            {
                if (password_verify($password, $user['password']))
                {
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['logged'] = 1;
                    Helper::redirect('dashboard');
                }
                else
                {
                    $this->addAlert('Invalid Password !', 'danger');
                }
            }
            else
            {
                $this->addAlert('Invalid Username !', 'danger');
            }

        }

        $this->display('login', true);
    }


    /**
     * User logout
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function logout()
    {
        if (isset($_SESSION["logged"])) unset($_SESSION["logged"]);
        if (isset($_SESSION["user"])) unset($_SESSION["user"]);
        Helper::redirect('login');
    }


    /**
     * Analyze data
     * @author CodySeller <https://codyseller.com>
     * @since 1.4
     */
    protected function analyze()
    {
        $link = new Link($this->db, $this->config);
        $server = new Server($this->db, $this->config);
        $proxy = new Proxy();

        $tl = number_format($link->getTotalLinks());
        $tv = number_format($link->getTotalViews());
        $bl = number_format(count($link->getAll('broken')));
        $serL = $server->getAll();
        $serl = count($serL);
        $diffT = $link->getDTY();
        $reffT = $link->getRDT();
        $sdPN = [];
        $sdPV = [];
        $t = $tv;
        $drSize = Helper::GetDirectorySize(ROOT . '/data/cache/');

        $sdPN[] = 'Main server';
        $sdPV[] = 0;
        foreach ($serL as $s)
        {
            $sdPN[] = $s['name'];
            $sdPV[] = $s['playbacks'];
            $t -= $s['playbacks'];
        }
        $sdPV[0] = $t;

        $activeProxy = $proxy->getProxyList();
        $brokenProxy = $proxy->getProxyList('broken');
        $nap = !empty($activeProxy) && is_array($activeProxy) ? count($activeProxy) : 0;
        $nbp = !empty($brokenProxy) && is_array($brokenProxy) ? count($brokenProxy) : 0;

        $mal = $link->getMostViewed();
        $ral = $link->getRecentlyAdded();

        $data = ['totalLinks' => $tl, 'totalViews' => $tv, 'brokenLinks' => $bl, 'maLinks' => $mal, 'raLinks' => $ral, 'totalServers' => $serl, 'dft' => $diffT, 'rft' => $reffT, 'serL' => [$sdPV, $sdPN], 'drSize' => $drSize, 'proxy' => [$nap, $nbp], 'gauths' => $this->getGDA() ];

        $this->addData($data, 'data');

    }


    /**
     * Get gdrive counter
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function getGDA()
    {
        $auths = $this
            ->db
            ->rawQuery('SELECT status, count(1) as c From drive_auth Group by status');

        $resp = ['active' => 0, 'broken' => 0];

        if (!empty($auths) && is_array($auths))
        {
            foreach ($auths as $a)
            {
                switch ($a['status'])
                {
                    case '0':
                        $resp['active'] = $a['c'];
                    break;

                    case '1':
                        $resp['broken'] = $a['c'];
                    break;
                }

            }
        }

        return $resp;
    }

    /**
     * Settings page
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function settings($action = '', $sAction = '', $id = '')
    {

        if (!empty($action))
        {
            switch ($action)
            {

                case 'general':

                    $this->setTitle('General Settings | ' . APP_NAME . ' Application');

                    if (helper::isPost())
                    {

                        $logo = Helper::getReqData('logo');
                        $favicon = Helper::getReqData('favicon');
                        $darkTheme = Helper::getReqData('dark_theme') == 'on' ? 1 : 0;
                        $timezone = Helper::getReqData('timezone');
                        $player = Helper::getReqData('player');
                        $playerSlug = str_replace(' ', '', Helper::getReqData('playerSlug'));
                        $showServers = Helper::getReqData('show_servers') == 'on' ? 1 : 0;
                        $isAdblocker = Helper::getReqData('isAdblocker') == 'on' ? 1 : 0;
                        $vPreloader = Helper::getReqData('v_preloader') == 'on' ? 1 : 0;
                        $jsLicense = Helper::getReqData('jw_license');
                        $sublist = Helper::getReqData('sublist');
                        $defaultVideo = Helper::getReqData('default_video');

                        if (in_array($playerSlug, $this->actions) && $playerSlug != 'video')
                        {
                            $playerSlug = 'video';
                            $this->addAlert("You can not use this slug. choose another one !", 'warning');
                        }

                        if (!in_array($player, ['jw', 'plyr'])) $player = 'jw';
                        if (empty($playerSlug)) $playerSlug = 'video';
                        if (!empty($sublist))
                        {
                            $sublist = str_replace(' ', '', strtolower($sublist));
                            $sublist = explode(',', $sublist);
                        }
                        else
                        {
                            $sublist = [];
                        }
                        $sublist = json_encode($sublist);

                        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0)
                        {
                            $piname = $_FILES['logo']['name'];
                            $pitmp = $_FILES['logo']['tmp_name'];

                            $upname = Helper::uploadImg($piname, $pitmp);
                            if (!$upname)
                            {
                                $this->addAlert("Logo image upload failed. -> Invalid file format !", 'warning');
                            }
                            else
                            {
                                $logo = $upname;
                            }

                        }
                        else
                        {
                            if (!empty($this->config['logo']) && empty($logo))
                            {
                                if (file_exists(ROOT . '/uploads/' . $this->config['logo']))
                                {
                                    unlink(ROOT . '/uploads/' . $this->config['logo']);
                                }
                                $logo = '';
                            }
                        }

                        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0)
                        {
                            $piname = $_FILES['favicon']['name'];
                            $pitmp = $_FILES['favicon']['tmp_name'];

                            $upname = Helper::uploadImg($piname, $pitmp);
                            if (!$upname)
                            {
                                $this->addAlert("Favicon upload failed. -> Invalid file format !", 'warning');
                            }
                            else
                            {
                                $favicon = $upname;
                            }

                        }
                        else
                        {
                            if (!empty($this->config['favicon']) && empty($logo))
                            {
                                if (file_exists(ROOT . '/uploads/' . $this->config['favicon']))
                                {
                                    unlink(ROOT . '/uploads/' . $this->config['favicon']);
                                }
                                $favicon = '';
                            }
                        }

                        $data = ['sublist' => $sublist, 'timezone' => $timezone, 'player' => $player, 'dark_theme' => $darkTheme, 'playerSlug' => $playerSlug, 'logo' => $logo, 'favicon' => $favicon, 'showServers' => $showServers, 'default_video' => $defaultVideo, 'isAdblocker' => $isAdblocker, 'v_preloader' => $vPreloader, 'jw_license' =>$jsLicense  ];

                        $this->updateSettings($data);

                        $this->addAlert('Settings saved successfully !', 'success');
                        $this->saveAlerts();
                        Helper::redirect('settings/general');

                    }

                    $this->display('general-settings');

                    break;

                case 'backup':

                    $this->setTitle('Backup | ' . APP_NAME . ' Application');

                    if (isset($_GET['i']))
                    {
                        $this->updateSettings(['last_backup' => Helper::tnow() ]);
                        Helper::exportDB();
                        Helper::redirect('settings/backup');
                    }

                    $this->display('backup');
                    break;

                case 'proxy':

                    $this->setTitle('Proxy settings | ' . APP_NAME . ' Application');

                    $proxy = new Proxy();

                    if (Helper::isPost())
                    {
                        $acpList = helper::getReqData('activeProxy');
                        $bcpList = helper::getReqData('brokenProxy');
                        $proxyUser = helper::getReqData('proxyUser');
                        $proxyPass = helper::getReqData('proxyPass');

                        if (!empty($acpList))
                        {
                            $acpList = explode(',', str_replace(' ', '', $acpList));
                            if (!$proxy->saveProxy($acpList))
                            {
                                if ($proxy->hasError())
                                {
                                    $this->addAlert($proxy->getError() , 'danger');
                                }
                            }
                        }
                        else
                        {
                            $proxy->clear();
                        }

                        if (!empty($bcpList))
                        {

                            $bcpList = explode(',', str_replace(' ', '', $bcpList));
                            if (!$proxy->saveBrokenProxy($bcpList, 'new'))
                            {
                                if ($proxy->hasError())
                                {
                                    $this->addAlert($proxy->getError() , 'danger');
                                }
                            }
                        }
                        else
                        {
                            $proxy->clear('broken');
                        }

                        $this->updateSettings(['proxyUser' => $proxyUser, 'proxyPass' => $proxyPass]);

                        helper::redirect('settings/proxy');

                    }

                    $activeProxy = $proxy->getProxyList();
                    $brokenProxy = $proxy->getProxyList('broken');
                    $nap = !empty($activeProxy) && is_array($activeProxy) ? count($activeProxy) : 0;
                    $nbp = !empty($brokenProxy) && is_array($brokenProxy) ? count($brokenProxy) : 0;
                    $activeProxy = !empty($activeProxy) && is_array($activeProxy) ? implode(',' . PHP_EOL, $activeProxy) : '';
                    $brokenProxy = !empty($brokenProxy) && is_array($brokenProxy) ? implode(',' . PHP_EOL, $brokenProxy) : '';

                    $this->addData($activeProxy, 'activeProxy');
                    $this->addData($brokenProxy, 'brokenProxy');
                    $this->addData($nap, 'nap');
                    $this->addData($nbp, 'nbp');

                    $this->display('proxy');

                    break;

                case 'gauth':

                    $this->setTitle('GAuths settings | ' . APP_NAME . ' Application');

                    if (!empty($sAction))
                    {

                        switch ($sAction)
                        {
                            case 'new':
                            case 'edit':

                                $isEdit = false;

                                $auth = ['email' => '', 'client_id' => '', 'client_secret' => '', 'refresh_token' => ''];

                                if ($sAction == 'edit')
                                {
                                    if (!empty($id))
                                    {
                                        $this
                                            ->db
                                            ->where('id', $id);
                                        $auth = $this
                                            ->db
                                            ->getOne('drive_auth');
                                        $isEdit = true;
                                        if ($this
                                            ->db->count == 0)
                                        {
                                            $this->_404();
                                        }
                                    }
                                    else
                                    {
                                        $this->_404();
                                    }
                                }

                                if (Helper::isPost())
                                {
                                    $email = Helper::getReqData('email');
                                    $clientId = Helper::getReqData('client_id');
                                    $clientSecret = Helper::getReqData('client_secret');
                                    $refreshToken = Helper::getReqData('refresh_token');

                                    if (empty($clientId))
                                    {
                                        $this->addAlert('Client ID is required !', 'danger');
                                    }

                                    if (empty($clientSecret))
                                    {
                                        $this->addAlert('Client Secret is required !', 'danger');
                                    }

                                    if (empty($refreshToken))
                                    {
                                        $this->addAlert('Refresh Token is required !', 'danger');
                                    }

                                    if (!$this->hasAlerts())
                                    {
                                        $data = ['email' => $email, 'client_id' => $clientId, 'client_secret' => $clientSecret, 'refresh_token' => $refreshToken];

                                        if (!$isEdit)
                                        {
                                            $id = $this
                                                ->db
                                                ->insert('drive_auth', $data);
                                        }
                                        else
                                        {
                                            $data['access_token'] = '';
                                            $this
                                                ->db
                                                ->where('id', $id);
                                            $this
                                                ->db
                                                ->update('drive_auth', $data);
                                        }

                                        if (!empty($id))
                                        {
                                            new GDrive($this->db, $this->config, $id);
                                        }

                                        Helper::redirect('settings/gauth');

                                    }

                                }

                                $this->addData($auth, 'auth');
                                $this->display('__new-gdrive-auth');

                            break;

                            case 'del':

                                if (!empty($id) && is_numeric($id))
                                {
                                    $this
                                        ->db
                                        ->where('id', $id);
                                    $this
                                        ->db
                                        ->delete('drive_auth');
                                }
                                Helper::redirect('settings/gauth');

                            break;

                            default:
                                $this->_404();
                            break;

                        }

                    }
                    else
                    {
                        $gdriveAuths = $this
                            ->db
                            ->get('drive_auth');

                        $this->addData($gdriveAuths, 'auths');
                        $this->display('gdrive-auth');
                    }

                    break;

                }
            }
            else
            {
                $this->_404();
            }

    }

    /**
     * Ads page
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function ads($action = '', $id = '')
    {

        $this->setTitle('Advertisement | ' . APP_NAME . ' Application');



        if (Helper::isPost())
        {
            switch ($action)
            {

                

                case 'save-vast':

                    $isEdit = false;

                    $id = Helper::getReqData('id');
                    $title = Helper::getReqData('title');
                    $xml = Helper::getReqData('xml');
                    $type = Helper::getReqData('type');
                    $offset = Helper::getReqData('offset');
                    $skipOffset = Helper::getReqData('skip-offset');

                    if (!empty($id))
                    {
                        $isEdit = true;
                    }

                    if (empty($xml))
                    {
                        $this->addAlert('XML file is required !', 'danger');
                    }

                    if (empty($offset))
                    {
                        $this->addAlert('Offset is required !', 'danger');
                    }

                    if (empty($type))
                    {
                        $this->addAlert('Ad type is required !', 'danger');
                    }

                    if (!$this->hasAlerts())
                    {

                        $adcode = ['tag' => $xml, 'offset' => $offset];

                        //nonlinear
                        if ($type != 'nonlinear')
                        {
                            if (empty($skipOffset) || !is_numeric($skipOffset))
                            {
                                $skipOffset = 5;
                            }
                            $adcode['skipoffset'] = $skipOffset;
                        }
                        else
                        {
                            $adcode['type'] = 'nonlinear';
                        }

                        $adcode = json_encode($adcode);
                        $data = ['title' => $title, 'type' => 'vast', 'code' => $adcode];

                        if (!$isEdit)
                        {
                            $id = $this
                                ->db
                                ->insert('ads', $data);
                            if ($id)
                            {
                                $this->addAlert('VAST Ad Saved Successfully !', 'success');
                            }
                            else
                            {
                                $this->addAlert('Something went wrong!', 'danger');
                            }
                        }
                        else
                        {
                            $this
                                ->db
                                ->where('id', $id);
                            if (!$this
                                ->db
                                ->update('ads', $data))
                            {
                                $this->addAlert('Something went wrong!', 'danger');
                            }
                            else
                            {
                                $this->addAlert('VAST Ad Saved Successfully !', 'success');
                            }
                        }

                        $this->saveAlerts();

                    }

                break;



                case 'save-popad':
                    $adcode = $_POST['popads'];

                    if (!empty($adcode))
                    {
                        $adcode = base64_encode($adcode);
                    }

                    $this
                        ->db
                        ->where('type', 'popad');
                    $this
                        ->db
                        ->update('ads', ['code' => $adcode]);
                break;

            }
            Helper::redirect('ads');
        }

        if($action == 'del')
        {

            if (!empty($id) && is_numeric($id))
            {
                $this->db->where('id', $id);
                $this->db->delete('ads');

                $this->addAlert('Vast Ad item deleted successfully !', 'success');
                $this->saveAlerts();
                Helper::redirect('ads');
            }
            else
            {
                $this->_404();
            }
        }

        $ads = [];

        $this
            ->db
            ->where('type', 'vast');
        $ads['vast'] = $this
            ->db
            ->get('ads');

        $this
            ->db
            ->where('type', 'popad');
        $ads['popad'] = $this
            ->db
            ->get('ads');
        $ads['popad'] = $ads['popad'][0]['code'];

        $this->addData($ads, 'ads');
        $this->display('ads');
    }

    /**
     * Ajax request
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function ajax()
    {
        $resp = ['success' => false];
        $err = '';

        if (isset($_GET['type']))
        {
            switch ($_GET['type'])
            {

                case 'clear-cache':

                    $files = glob(ROOT . '/data/cache/*');
                    foreach ($files as $file)
                    {
                        if (is_file($file))
                        {
                            unlink($file);
                        }
                    }
                    $resp = ['success' => true];

                break;

                case 'check-proxy':

                    $ip = Helper::getReqData('ip');

                    if (!empty($ip))
                    {
                        $proxy = new Proxy($this->config['proxyUser'], $this->config['proxyPass']);

                        if ($proxy->check($ip))
                        {
                            $resp = ['success' => true];
                        }
                    }

                break;

                case 'refresh-gauth':

                    $id = Helper::getReqData('id');

                    if (!empty($id) && is_numeric($id))
                    {
                        $drive = new GDrive($this->db, $this->config, $id);

                        if ($drive->isValidAuth())
                        {
                            $resp = ['success' => true];
                        }

                    }

                break;

                case 'refresh-server':

                    $id = Helper::getReqData('id');

                    if (!empty($id))
                    {
                        $server = new Server($this->db, $this->config);
                        if ($server->load($id) && $server->isHit())
                        {
                            $resp = ['success' => true];
                        }
                    }

                break;

                case 'delete-link':

                    $id = Helper::getReqData('id');

                    if (!empty($id) && is_numeric($id))
                    {
                        $link = new Link($this->db, $this->config);

                        if ($link->delete($id))
                        {
                            $resp = ['success' => true];
                        }
                    }
                break;

                case 'delete-link-list':

                    $ids = Helper::getReqData('ids');

                    if (!empty($ids))
                    {
                        $link = new Link($this->db, $this->config);
                        $ids = explode(',', str_replace(' ', '', $ids));

                        if ($link->multiDelete($ids))
                        {
                            $resp = ['success' => true];
                        }
                    }
                break;

                case 'import-link':

                    $url = Helper::getReqData('url');

                    if (!empty($url) && Helper::isUrl($url))
                    {
                        sleep(1);
                        $type = Helper::getLinkType($url);

                        if ($type != 'Direct')
                        {
                            $link = new Link($this->db, $this->config);

                            if (!IS_DUPLICATE)
                            {
                                $rep = $link->search($url);
                                if ($rep !== false)
                                {
                                    $l = PROOT . '/links/edit/' . $rep['id'];
                                    $err = 'This link is already exist !&nbsp; <a href="' . $l . '" target="_blank class="alert-link">view exist link</a>';
                                }
                            }

                            if (empty($err))
                            {
                                $data = ['main_link' => $url, 'type' => $type, 'status' => 0

                                ];

                                $link->assign($data)->save();

                                if (!$link->hasError())
                                {
                                    $title = $link->obj['title'];
                                    $slug = $link->obj['slug'];
                                    $plyr = Helper::getPlyrLink($this->config['playerSlug'], $slug);
                                    if (empty($title)) $title = $link->obj['main_link'];

                                }
                                else
                                {
                                    $err = $link->getError();
                                }
                            }

                        }
                        else
                        {
                            $err = 'Link Format Not Supported !';
                        }

                    }
                    else
                    {
                        $err = 'Invalid URL !';
                    }

                    if (empty($err))
                    {
                        $resp = ['success' => true, 'title' => $title, 'plyr' => $plyr];
                    }
                    else
                    {
                        $resp = ['success' => false, 'error' => $err];
                    }

                    break;

                }
        }

        $this->jsonResponse($resp);

    }

    /**
     * Servers page
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function servers($action = '', $id = '')
    {

        $this->setTitle('Servers | ' . APP_NAME . ' Application');
        $server = new Server($this->db, $this->config);

        switch ($action)
        {
            case 'del':

                $id = Helper::clean($id);
                if (!empty($id))
                {
                    $server->del($id);
                }
                Helper::redirect('servers');
            break;

            case 'status':
                $id = Helper::clean($id);
                if (!empty($id) && $server->load($id))
                {
                    $server->changeStatus();
                }
                Helper::redirect('servers');
            break;

            default:
                if (Helper::isPost())
                {

                    $name = Helper::getReqData('name');
                    $domain = Helper::getReqData('domain');
                    $id = Helper::getReqData('id');
                    $isBroken = 1;

                    if (!empty($id))
                    {
                        if (!$server->load($id))
                        {
                            $this->error = 'Something went wrong !';
                        }
                    }

                    if (!empty($domain))
                    {
                        if (Helper::isUrl($domain))
                        {

                            $resp = Helper::isI($domain . '/stream/test');

                            if ($resp == 200)
                            {
                                $isBroken = 0;

                            }

                        }
                        else
                        {
                            $this->addAlert('Invalid domain URL !', 'danger');
                        }
                    }
                    else
                    {
                        $this->addAlert('Domain is required !', 'danger');
                    }

                    if (empty($name)) $name = 'My server';

                    if (!$this->hasAlerts())
                    {
                        $data = ['name' => $name, 'domain' => $domain, 'is_broken' => $isBroken];
                        $server->assign($data)->save();

                        if (!$server->hasError())
                        {
                            $this->addAlert('Saved changes successfully !', 'success');
                        }
                        else
                        {
                            $this->addAlert($server->getError() , 'danger');
                        }
                        $this->saveAlerts();
                        Helper::redirect('servers');

                    }

                }

                $servers = $server->getAll();
                $this->addData($servers, 'servers');
                $this->display('servers');

                break;
            }

    }

    /**
     * API request
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function api($action = '')
    {

        $link = new Link($this->db, $this->config);
        $resp = ['status' => 'failed'];

        switch ($action)
        {
            case 'refresh':

                $id = Helper::getReqData('id');
                $isHit = false;

                if (!empty($id))
                {

                    if ($link->isExit($id))
                    {
                        $link->load($id, 'slug');
                        $file = $link->obj;

                        if ($file['type'] == 'GDrive')
                        {

                            if (!empty($file['data']))
                            {
                                $isHit = Helper::isHit($file['data'], $id);
                            }

                            if (empty($file['data']) || !$isHit)
                            {
                                if (!empty($this->getDriveS($link)))
                                {
                                    $isHit = true;
                                }
                            }

                            if ($isHit) $resp = ['status' => 'success'];

                        }

                    }

                }

                break;
            }

            $this->jsonResponse($resp);

    }

    /**
     * Get gdrive sources
     * @author CodySeller <https://codyseller.com>
     * @since 1.5
     */
    protected function getDriveS($link, $alt = false)
    {
        $dl = $isBroken = false;
        $file = $link->obj;
        $isA = false;

        if (!($alt && $link->obj['is_alt']))
        {
            $link->refresh($alt);

            if (!$link->hasError())
            {

                if (!empty($link->obj['data']))
                {
                    $file = $link->obj;
                }
                else
                {
                    $dl = true;
                    $dlSources = [['file' => PROOT . "/stream/360/{$file['slug']}/" . GDRIVE_IDENTIFY, 'q' => 360]];
                }
            }
            else
            {
                $isBroken = true;
            }
        }

        if (!$isBroken)
        {
            return !$dl ? Helper::getDriveData($file) : $dlSources;
        }
        else
        {
            return false;
        }

    }

    /**
     * Load alternative links
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function getAltS($link)
    {
        $file = $link->obj;
        $isBroken = false;

        $type = Helper::getLinkType($file['alt_link']);
        $this->videoType = $type;
        switch ($type)
        {
            case 'GDrive':

                $sources = $this->getDriveS($link, true);

            break;

            case 'GPhoto':

                $gphoto = new GPhoto();
                $sources = $gphoto->get($file['alt_link']);

            break;

            case 'OneDrive':

                $oneDrive = new OneDrive();
                $sources = $oneDrive->get($file['alt_link']);

            break;

            case 'Yandex':

                $lastUpdated = $file['updated_at'];
                $timeFirst = strtotime($lastUpdated);
                $timeSecond = strtotime(Helper::tnow());
                $differenceInSeconds = $timeSecond - $timeFirst;

                if ((empty($file['data']) && $link->obj['is_alt']) || (!empty($file['data']) && !($differenceInSeconds < 10800  && $differenceInSeconds > 1)))
                {
                    $link->refresh(true, YANDEX_IDENTIFY);
                    if (!$link->hasError())
                    {
                        $file = $link->obj;
                    }
                }

                if (!empty($file['data']))
                {
                    $sources = ['file' => $file['data'], 'q' => ''];
                }

            break;

            case 'Direct':

                if (Helper::isI($file['alt_link']) == 200)
                {
                    $sources = [['file' => PROOT . "/stream/480/{$file['slug']}/" . DIRECT_IDENTIFY, 'q' => '360']];
                }

            break;

        }

        if (empty($sources))
        {
            $isBroken = true;
        }

        return !$isBroken ? $sources : false;

    }

    /**
     * Load main links
     * @author CodySeller <https://codyseller.com>
     * @since 2.2
     */
    protected function getMainS($link)
    {
        $file = $link->obj;
        $type = $file['type'];
        $isBroken = false;

        $this->videoType = $type;

        switch ($type)
        {
            case 'GDrive':

                if (empty($file['data']) || $file['status'] == 2)
                {
                    $sources = $this->getDriveS($link);
                }
                else
                {
                    $sources = Helper::getDriveData($file);
                }

            break;

            case 'GPhoto':

                $gphoto = new GPhoto();
                $sources = $gphoto->get($file['main_link']);

            break;

            case 'OneDrive':

                $oneDrive = new OneDrive();
                $sources = $oneDrive->get($file['main_link']);

            break;

            case 'Yandex':

                $lastUpdated = $file['updated_at'];
                $timeFirst = strtotime($lastUpdated);
                $timeSecond = strtotime(Helper::tnow());
                $differenceInSeconds = $timeSecond - $timeFirst;


                if (empty($file['data']) || !($differenceInSeconds < 10800  && $differenceInSeconds > 1))
                {
                    $link->refresh(false, YANDEX_IDENTIFY);
                    if (!$link->hasError())
                    {
                        $file = $link->obj;
                    }
                }

                if (!empty($file['data']))
                {
                    $sources = ['file' => $file['data'], 'q' => ''];
                }

            break;

            case 'Direct':

                if (Helper::isI($file['main_link']) == 200)
                {
                    $sources = [['file' => PROOT . "/stream/360/{$file['slug']}/" . DIRECT_IDENTIFY, 'q' => '360']];
                    if ($file['status'] == 2) $link->broken(false);
                }
                else
                {
                    if ($file['status'] == 2) $link->broken();
                }

                break;

            }

            if (empty($sources))
            {
                $isBroken = true;

                if ($type != 'GDrive')
                {
                    if (!$link->isBroken())
                    {
                        $link->broken();
                    }
                }
            }
            else
            {
                if ($type != 'GDrive')
                {
                    if ($link->isBroken())
                    {
                        $link->broken(false);
                    }
                }
            }

            // return false;
            return !$isBroken ? $sources : false;

        }

        /**
         * Video page
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function video($id = '')
        {
            $this->firewall();

            $player = $this->getPlyr();
            $isBroken = $isOk = $isY = false;
            $isMain = true;
            $servers = [];

            $link = new Link($this->db, $this->config);

            if (!empty($link))
            {
                if ($link->isExit($id))
                {
                    $link->load($id, 'slug');
                    $file = $link->obj;
                    $type = $file['type'];

                    if ($file['status'] != 1)
                    {
                        $link->viewed();

                        $sources = $this->getMainS($link);

                        if (empty($sources))
                        {
                            $isMain = false;
                            $isBroken = true;

                            if (!empty($file['alt_link']))
                            {
                                $sources = $this->getAltS($link);

                                if (!empty($sources))
                                {
                                    $isBroken = false;
                                }
                            }
                        }

                        if (!$isBroken)
                        {

                            if ($type == 'GDrive' && $isMain)
                            {
                                $sId = Helper::getReqData('sid');
                                $serverObj = new Server($this->db, $this->config);
                                $servers = $serverObj->getAll(true);
                                $acA = 'main_d_001';
                                $server = $serverObj->getOne($sId);

                                if (!empty($server))
                                {
                                    if (empty($sId))
                                    {
                                        $acT = ['main_d_001', $server['domain']];
                                        $acA = $acT[array_rand($acT) ];
                                    }
                                    else
                                    {

                                        $acA = $server['domain'];
                                    }

                                    if ($acA != 'main_d_001')
                                    {
                                        $sources = Helper::addSD($sources, $acA);
                                        $serverObj->load($server);
                                        $serverObj->addPlayback();
                                    }
                                }
                            }

                            $pData = ['sources' => $sources, 'subs' => Helper::cleanSub($file['subtitles']) , 'title' => $file['title'], 'poster' => Helper::getBanner($file['preview_img']) , 'type' => $this->videoType, 'player' => $player];
                        }
                        else
                        {
                            if (!empty($this->config['default_video']))
                            {
                                $pData = ['sources' => [['file' => $this->config['default_video'], 'q' => 360]], 'type' => 'Direct', 'player' => $player];
                            }
                            else
                            {
                                die('<h1>Video is unavailable !</h1>');
                            }
                        }

                        if ($this->videoType == 'Yandex')
                        {
                            $isY = true;
                            $player = $pData['player'] = 'plyr';
                        }

                        $logo = PROOT . '/uploads/' . $this->config['logo'];

                        $this->addData(@base64_decode($this->getPopAds()) , 'popads');
                        $this->addData($this->getPlyrAds() , 'ads');
                        $this->addData($logo, 'logo');

                        $this->addData($servers, 'servers');
                        $this->addData(Helper::formatPlayerData($pData, $isY) , 'data');
                        $this->display('players/' . $player, true);
                        $isOk = true;

                    }

                }

            }

            if(!$isOk) $this->_404();
            
        }

        /**
         * Get vast ads
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function getPlyrAds()
        {
            $this
                ->db
                ->where('type', 'vast');
            $ads = $this
                ->db
                ->get('ads');

            if (!empty($ads) && is_array($ads))
            {
                $adList = [];
                foreach ($ads as $ad)
                {
                    if (!empty($ad['code']))
                    {
                        $adList[] = $ad['code'];
                    }
                }
                $adList = implode(', ', $adList);
                return $adList;
            }
            return '';
        }

        /**
         * Set page title
         * @author CodySeller <https://codyseller.com>
         * @since 2.2
         */
        protected function setTitle($t = '')
        {
            $this->addData($t, 'ptitle');
        }

        /**
         * Get page title
         * @author CodySeller <https://codyseller.com>
         * @since 2.2
         */
        protected function getTitle()
        {
            return isset($this->data['ptitle']) ? $this->data['ptitle'] : '';
        }

        /**
         * Get popads
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function getPopAds()
        {
            $this
                ->db
                ->where('type', 'popad');
            $popads = $this
                ->db
                ->get('ads');
            return $popads[0]['code'];
        }

        /**
         * Links page
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function links($action = '', $id = '')
        {

            $link = new Link($this->db, $this->config);

            if (!empty($action))
            {
                switch ($action)
                {
                    case 'new':
                    case 'edit':

                        $this->setTitle('Add/Edit Link | ' . APP_NAME . ' Application');

                        $isEdit = ($action == 'edit') ? true : false;

                        if ($isEdit)
                        {
                            if (!empty($id) && is_numeric($id) && $link->isExit($id, 'id'))
                            {
                                $link->load($id);
                                $isEdit = true;
                            }
                        }

                        if (Helper::isPost())
                        {

                            $mainLink = Helper::getReqData('main_link');
                            $altLink = Helper::getReqData('alt_link');
                            $slug = Helper::getReqData('slug');
                            $status = Helper::getReqData('status');
                            $title = Helper::getReqData('title');
                            $sublist = Helper::getReqData('sub');
                            $previewImg = $type = '';
                            $subAllowedExt = ['vtt', 'srt', 'dfxp', 'ttml', 'xml'];
                            $imgAllowedExt = ['jpg', 'jpeg', 'png'];
                            $subs = [];

                            if (empty($mainLink))
                            {
                                $this->addAlert('Main link is required !', 'danger');
                            }
                            else
                            {
                                if (!Helper::isUrl($mainLink))
                                {
                                    $this->addAlert('Invalid URL provided for main link !', 'danger');
                                }
                                else
                                {
                                    if (!IS_DUPLICATE)
                                    {
                                        $rep = $link->search($mainLink);
                                        if ($rep !== false)
                                        {
                                            $l = PROOT . '/links/edit/' . $rep['id'];
                                            $this->addAlert('Main link is already exist !&nbsp; <a href="' . $l . '" class="alert-link">view exist link</a>', 'danger');
                                        }
                                    }
                                }
                            }

                            if (!empty($altLink) && !Helper::isUrl($altLink))
                            {
                                $this->addAlert('Invalid URL provided for alternative link !', 'danger');
                            }

                            if (!empty($slug))
                            {
                                if ($link->isExit($slug))
                                {
                                    $this->addAlert('Video slug is already exist !', 'danger');
                                }
                            }

                            if (!$this->hasAlerts())
                            {

                                if (isset($_FILES['sub']))
                                {
                                    //attempt to upload files
                                    $upload = new Upload(SUB_UPLOAD_DIR);
                                    $upload->setExt($subAllowedExt);

                                    if (!$upload->hasError())
                                    {
                                        $upload->upload($_FILES['sub']);
                                        $resp = $upload->getResp();

                                        if (isset($resp['s']))
                                        {
                                            foreach ($sublist as $sk => $sv)
                                            {
                                                if (array_key_exists($sk, $resp['s']))
                                                {
                                                    if ($isEdit)
                                                    {
                                                        if (!empty($sublist[$sk]['file']))
                                                        {
                                                            $subFile = ROOT . '/uploads/' . SUB_UPLOAD_DIR . '/' . $sublist[$sk]['file'];
                                                            if (file_exists($subFile))
                                                            {
                                                                unlink($subFile);
                                                            }
                                                        }
                                                        $sublist[$sk]['file'] = $resp['s'][$sk];
                                                    }
                                                    else
                                                    {
                                                        $subs[] = json_encode(['label' => $sv['label'], 'file' => $resp['s'][$sk]]);
                                                    }

                                                }
                                            }
                                        }

                                        if (isset($resp['e']))
                                        {
                                            foreach ($resp['e'] as $e)
                                            {
                                                $this->addAlert($e, 'warning');
                                            }
                                        }

                                    }
                                    else
                                    {
                                        $this->addAlert($upload->getError() , 'warning');
                                    }
                                }

                                if (isset($_FILES['preview_image']))
                                {
                                    $upload = new Upload(BANNER_UPLOAD_DIR);
                                    $upload->setExt($imgAllowedExt);

                                    if (!$upload->hasError())
                                    {
                                        $upload->upload($_FILES['preview_image']);
                                        $resp = $upload->getResp();

                                        if (isset($resp['s'][0]))
                                        {
                                            $previewImg = $resp['s'][0];
                                        }
                                        else
                                        {
                                            if (isset($resp['e']))
                                            {
                                                foreach ($resp['e'] as $e)
                                                {
                                                    $this->addAlert($e, 'warning');
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $this->addAlert($upload->getError() , 'warning');
                                    }

                                }

                                if ($isEdit && isset($_POST['preview_image']))
                                {
                                    $op = $_POST['preview_image'];
                                    if ((!empty($previewImg) && $previewImg != $op) || isset($_POST['pre_img_del']))
                                    {
                                        $rp = ROOT . '/uploads/' . BANNER_UPLOAD_DIR . '/' . $op;
                                        if (file_exists($rp))
                                        {
                                            unlink($rp);
                                        }
                                    }
                                    else
                                    {
                                        $previewImg = $op;
                                    }
                                }

                                if ($isEdit)
                                {
                                    if (!empty($sublist) && is_array($sublist))
                                    {
                                        foreach ($sublist as $sub)
                                        {
                                            if (is_array($sub) && !empty($sub['file']))
                                            {
                                                if (!isset($sub['is_remove']) || (isset($sub['is_remove']) && $sub['is_remove'] == 0))
                                                {
                                                    if (isset($sub['is_remove']))
                                                    {
                                                        unset($sub['is_remove']);
                                                    }

                                                    $subs[] = json_encode($sub);
                                                }
                                                else
                                                {
                                                    $subFile = ROOT . '/uploads/' . SUB_UPLOAD_DIR . '/' . $sub['file'];
                                                    if (file_exists($subFile))
                                                    {
                                                        unlink($subFile);
                                                    }
                                                }

                                            }
                                        }
                                    }
                                }

                                $subs = !empty($subs) && is_array($subs) ? implode(',', $subs) : '';
                                $status = !empty($status) && $status == 'active' ? 0 : 1;
                                $type = Helper::getLinkType($mainLink);
                                $data = ['title' => $title, 'main_link' => $mainLink, 'alt_link' => $altLink, 'subtitles' => $subs, 'slug' => $slug, 'type' => $type, 'preview_img' => $previewImg, 'status' => $status];

                                $link->assign($data)->save();

                                if (!$link->hasError())
                                {
                                    $id = $link->getID();

                                    if (!$isEdit)
                                    {
                                        $this->addAlert('Link saved successfully !', 'success');
                                        $this->saveAlerts();

                                        Helper::redirect("links/edit/$id");
                                    }
                                    else
                                    {
                                        // Main::redirect("links/active");
                                        
                                    }

                                }
                                else
                                {
                                    $this->addAlert($link->getError() , 'danger');
                                }

                            }

                        }

                        if (!$isEdit)
                        {
                            $this->display('__new_link');
                        }
                        else
                        {

                            $l = $link->getObj();
                            $subtitles = $l['subtitles'];

                            if (!empty($subtitles))
                            {
                                $subList = @json_decode('[' . $subtitles . ']', true);
                                if (!empty($subList))
                                {
                                    $subtitles = $subList;
                                }
                            }

                            if (empty($subtitles))
                            {
                                $subtitles = [['label' => '', 'file' => '']];
                            }

                            $l['subtitles'] = $subtitles;
                            $this->addData($l, 'link');
                            $this->display('__edit_link');
                        }

                    break;

                    case 'all':

                        $this->setTitle('All Links | ' . APP_NAME . ' Application');

                        $links = $link->getAll();
                        $this->addData($links, 'links');
                        $this->display('links');

                    break;

                    default:
                        $this->_404();
                    break;
                }
            }
            else
            {
                $this->_404();
            }

        }

        /**
         * Bulk import page
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function bulk()
        {
            $this->setTitle('Bulk Import | ' . APP_NAME . ' Application');
            $this->display('bulk-import');
        }

        /**
         * Update settings
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function updateSettings($data = [])
        {
            foreach ($data as $config => $val)
            {
                $this
                    ->db
                    ->where('config', $config);
                $this
                    ->db
                    ->update('settings', ['var' => $val]);
            }
        }

        /**
         * Save application data
         * @author CodySeller <https://codyseller.com>
         * @since 1.4
         */
        protected function addData($data, $name = '')
        {
            if (!empty($name))
            {
                $this->data[$name] = $data;
            }
            else
            {
                $this->data = $data;
            }

        }

        /**
         * Save alerts in session
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function saveAlerts()
        {
            $_SESSION['alerts'] = $this->alerts;
        }

        /**
         * Add alert
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function addAlert($msg, $type)
        {

            if (!array_key_exists($type, $this->alerts))
            {
                $this->alerts[$type] = [];
            }

            $this->alerts[$type][] = $msg;

        }

        /**
         * Check alerts
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function hasAlerts($t = 'danger', $all = false)
        {
            if ((isset($this->alerts[$t]) && !empty($this->alerts[$t])) || ($all && !empty($this->alerts)))
            {
                return true;

            }

            return false;
        }

        /**
         * Disaply alerts
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function displayAlerts()
        {
            if ($this->hasAlerts('', true))
            {
                $alertHtml = '';

                foreach ($this->alerts as $k => $v)
                {
                    $alertHtml .= '<div class="alert alert-' . $k . '" role="alert"><b>Alert:&nbsp;</b>';
                    if (count($v) == 1)
                    {
                        $alertHtml .= $v[0];
                    }
                    else
                    {
                        $list = '<ul>';
                        foreach ($v as $al)
                        {
                            $list .= '<li>' . $al . '</li>';
                        }
                        $list .= '</ul>';
                        $alertHtml .= $list;
                    }
                    $alertHtml .= '</div>';
                }
                echo $alertHtml;
            }
        }

        /**
         * Get user's username
         * @since 1.3
         */
        protected function getUsername()
        {
            return $this->logged ? $_SESSION['user'] : '';
        }

        /**
         * Check adblock enbled or not
         * @since 2.2
         */
        protected function isAdblockEnabled()
        {
            return $this->config['isAdblocker'] == 1 ? true : false;
        }

        /**
         * Check video preloader
         * @since 2.2
         */
        protected function isPreloaderEnabled()
        {
            return $this->config['v_preloader'] == 1 ? true : false;
        }


        /**
         * Display template pages
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function display($template, $isBlank = false)
        {

            if (is_array($this->data))
            {
                foreach ($this->data as $k => $v)
                {
                    $$k = $v;
                }
            }
            else
            {
                $data = $this->data;
            }

            if (!file_exists(TEMPLATE . '/' . $template . '.php'))
            {
                //template file not found
                die('File ' . TEMPLATE . '/' . $template . '.php not found !');
            }

            if (!$isBlank)
            {
                $this->header();
                include (TEMPLATE . '/' . $template . '.php');
                $this->footer();
            }
            else
            {
                include (TEMPLATE . '/' . $template . '.php');
            }
        }

        /**
         * Template header
         * @since 1.3
         */
        protected function header()
        {
            include ($this->t(__FUNCTION__));
        }

        /**
         * Template footer
         * @since 1.3
         */
        protected function footer()
        {
            include ($this->t(__FUNCTION__));
        }

        /**
         * Get template part
         * @since 1.3
         */
        protected function t($template)
        {
            if (!file_exists(TEMPLATE . '/' . $template . '.php')) die('File ' . $template . ' does not exist !');
            return TEMPLATE . '/' . $template . '.php';
        }

        /**
         * Response JSON data
         * @since 1.3
         *
         */
        protected function jsonResponse($resp)
        {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: applicaton/json; charset=UTF-8");
            http_response_code(200);
            echo json_encode($resp);
            exit;
        }

        /**
         * Check active page (action)
         * @since 2.2
         */
        public function getAT($a)
        {
            return $this->action == $a ? 'active' : '';
        }

        /**
         * Get player
         * @since 2.2
         */
        public function getPlyr()
        {
            return $this->config['player'] == 'jw' ? 'jw' : 'plyr';
        }

        /**
         * Get player
         * @since 2.2
         */
        public function getJWLicense()
        {
            return $this->config['jw_license'];
        }

        /**
         * Firewall
         * @author CodySeller <https://codyseller.com>
         * @since 1.3
         */
        protected function firewall($s = false)
        {
            if (FIREWALL || ($s && !DIRECT_STREAM))
            {
                $domains = ALLOWED_DOMAINS;
                if (!is_array($domains)) $domains = [];

                if ($s)
                {
                    $domains[] = Helper::getHost();
                }

                if (!isset($_SERVER["HTTP_REFERER"]))
                {
                    $this->display('lol', true);
                    exit;
                }

                $referer = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
                if (empty($referer) || !in_array($referer, $domains))
                {
                    $this->_404();
                    exit;
                }
            }
        }

        /**
         * 404 page
         * @since 1.3
         *
         */
        protected function _404()
        {
            header('HTTP/1.1 404 Not Found');
            die('<h1>404 page not found !</h1>');
        }

        /**
         * Server Error
         * @since 1.3
         *
         */
        protected function _400()
        {
            header('HTTP/1.1 400 bad request');
            die('<h1>400 Bad Request !</h1>');
        }

        /**
         * Destructor: End of the application
         * @since 1.3
         *
         */
        public function __destruct()
        {
            $this
                ->db
                ->disconnectAll();
            $this->userId = NULL;
            $this->config = $this->db = NULL;
        }

    }

    //End
    
