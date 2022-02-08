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


class Link
{

    /**
     * Object data
     * @since 1.3
     **/
    public $obj = [];

    /**
     * Links table
     * @since 1.3
     **/    
    protected $tbl = 'links';

    /**
     * Blacklisted columns
     * @since 1.3
     **/    
    protected $blackListed = ['id','deleted','views','downloads'];

    /**
     * Database
     * @since 1.3
     **/
    protected $db;

    /**
     * Configuration
     * @since 1.3
     **/
    protected $config;

    /**
     * Link error
     * @since 1.3
     **/
    protected $error = '';

    protected $t = false;


    public function __construct($db, $config)
    {
        $this->db = $db;
        $this->config = $config;
        $this->initProperties();
    }


    /**
     * Assign data
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function assign($data = [])
    {
        $this->s($data['main_link']);

        foreach($data as $k => $v)
        {
            if(array_key_exists($k, $this->obj))
            {
                $this->obj[$k] = $v;
            }
        }

        if(!$this->isEdit() && empty($this->obj['slug']))
        {
            $slug = Helper::random();
            if($this->isExit($slug))
            {
                $slug = Helper::random();
            }
            $this->obj['slug'] = $slug;
        }



        if($data['type'] == 'GDrive' && $this->t)
        {
            $this->set();
        }

        if($data['type'] == 'Yandex' && $this->t)
        {
            $this->setY();
        }

        

        return $this;

    }


    /**
     * Save data
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function save()
    {
        if(!$this->hasError())
        {
            $this->beforeSave();
            
            if(!$this->isEdit())
            {
                $id = $this->db->insert($this->tbl, $this->getData());
                if($id)
                {
                    $this->obj['id'] = $id;
                }
                else{
                    $this->error = $this->db->getLastError();
                }
            }
            else
            {
                $this->db->where('id', $this->getID());
                if(!$this->db->update($this->tbl, $this->getData(), '1'))
                {
                    $this->error = 'Update Filed ! -> ' . $this->db->getLastError();
                }
            }
        }
        return !$this->hasError() ? true : false;
    }


    /**
     * Do something, before save data
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function beforeSave()
    {
        $this->obj['updated_at'] = Helper::tnow();
        if(!$this->isEdit())
        {
            if(empty($this->obj['title']))
            {
                $this->obj['title'] = 'Unknown';
            }

            $this->obj['created_at'] = Helper::tnow();
        }
    }

    protected function setY($alt = false)
    {
        $yandex = new Yandex($this->config);
        if(!$alt)
        {
            $url = $this->obj['main_link'];
        }
        else
        {
            $url = $this->obj['alt_link'];
        }

        $source = $yandex->get($url);

        if(!empty($source))
        {
            $this->obj['data'] = $source;
        }
        else
        {
            $this->error = 'Video not found !';
        }

    }


    /**
     * Set main source links
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function set($alt = false)
    {

        if(!$alt)
        {
            $gid = Helper::getDriveId($this->obj['main_link']);
        }
        else
        {
            $gid = Helper::getDriveId($this->obj['alt_link']);
        }

        

        if(!empty($gid))
        {
            $gdrive = new GDrive($this->db, $this->config);
            $gdrive->setKey($this->obj['slug']);
            $result = $gdrive->get($gid);

            if($result !== false)
            {
                if(is_array($result))
                {
                    if(empty($this->obj['title']))
                    {
                        $this->obj['title'] = $result['title'];
                    }
                    $this->obj['data'] = json_encode($result['data']);
                }
                else
                {
                    $this->obj['data'] = '';
                }
                if($this->isEdit() && $this->obj['status'] == 2 && !$alt)
                {
                    $this->broken(false);
                }
                
                $this->obj['is_alt'] = $alt ? 1 : 0;
            }
            else
            {
                if($gdrive->hasError())
                {
                    $this->error = $gdrive->getError();
                    if($this->isEdit() && !$this->isBroken())
                    {
                        $this->broken();
                    }

                }
            }
        }

    }

    public function broken($un = true)
    {
        if($this->isEdit())
        {
            $this->db->where('id', $this->getID());
            $status = $un ? 2 : 0;
            $this->obj['status'] = $status;
            $this->db->update($this->tbl, ['status'=>$status], '1');
        }
    }

    public function isBroken()
    {
        if($this->isEdit())
        {
            if($this->obj['status'] == 2)
            {
                return true;
            }
        }
        return false;
    }



    /**
     * Get currect link id
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function getID()
    {
        if($this->isEdit())
        {
            return $this->obj['id'];
        }
        return false;
    }


    /**
     * Check is edit or not
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function isEdit()
    {
        if(!empty($this->obj['id']))
        {
            return true;
        }
        return false;
    }


    /**
     * Get data for save
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function getData()
    {
        $data = $this->obj;
        foreach($this->blackListed as $bl)
        {
            if(array_key_exists($bl, $data))
            {
                unset($data[$bl]);
            }
        }
        return $data;
    }



    /**
     * Initialize properties
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    protected function initProperties()
    {
        $dbColumns = $this->db->rawQuery("DESCRIBE " . $this->tbl);
        if (!empty($dbColumns)) {
            foreach ($dbColumns as $col) {
                $this->obj[$col['Field']] = NULL;
            }
        }
    }

    public function isExit($s , $ty = 'slug')
    {
        if($ty == 'slug')
        {
            if($link = $this->findBySlug($s))
            {
                if($link['slug'] != $this->obj['slug']) 
                {
                    return true;
                }
            }
        }

        if($ty == 'id')
        {
            if($this->findById($s))
            {
                return true;
            }
        }

        return false;
    }

    public function getAll($s = '')
    {

        $st = [
            'active' => 0,
            'paused' => 1,
            'broken' => 2
        ];

        if(!empty($s))
        {
            $this->db->where("status", $st[$s]);
        }

        $this->db->orderBy("id","Desc");

        $links = $this->db->get($this->tbl);
        return $this->db->count > 0 ? $links : [];



    }


    /**
     * Find by slug
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function findBySlug($s)
    {
        $this->db->where('slug', $s);
        $link = $this->db->getOne($this->tbl);
        if($this->db->count > 0)
        {
            return $link;
        }
        return false;

    }


    /**
     * Find by ID
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function findById($id)
    {
        $this->db->where('id', $id);
        $link = $this->db->getOne($this->tbl);
        if($this->db->count > 0)
        {
            return $link;
        }
        return false;
    }


    /**
     * Check error
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function hasError()
    {
        if(!empty($this->error))
        {
            return true; 
        }
        return false;
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


    public function search($k)
    {
        if(Helper::isDrive($k))
        {
            $k = Helper::getDriveId($k);
        }

        if(strpos($this->obj['main_link'], $k) === false)
        {
            $this->db->where("main_link", "%$k%", 'like');
            $results = $this->db->getOne($this->tbl);
            if($this->db->count > 0)
            {
                return $results;
            }
        }   


        return false;
    }


    public function load($id, $t = 'id')
    {
        if($t == 'id')
        {
            $link = $this->findById($id);
        }
        else
        {
            $link = $this->findBySlug($id);
        }
      
        if($link)
        {
            foreach($link as $k => $v)
            {
                if(array_key_exists($k, $this->obj))
                {
                    $this->obj[$k] = $v;
                }
            }
            return true;
        }
        return false;

    }


    public function getObj()
    {
        return $this->obj;
    }

    public function refresh($alt = false, $s = '__001')
    {
        if($this->isEdit())
        {
            $this->error = '';
            if($s == '__001')
            {
                $this->set($alt);
            }
            else
            {
                $this->setY($alt);
            }
            
            $this->save();
        }
    }



    public function delete($id)
    {
        $this->db->where('id', $id);
        if($this->db->delete($this->tbl)){
            return true;
        }
        return false;
    }

    public function multiDelete($ids)
    {
        if(is_array($ids))
        {
            $this->db->where('id', $ids, 'IN');
            $this->db->delete($this->tbl);
            return true;
        }
        return false;
    }


    public function viewed()
    {
        if($this->isEdit())
        {
            $this->db->where('id', $this->getID());
            $this->db->update($this->tbl, ['views'=>$this->obj['views'] + 1], 1);
            
        }

    }

    public function getDTY()
    {
        $links =  $this->db->rawQuery('SELECT type, count(1) as c From links Group by type');

        $resp = [
            'GDrive' => 0,
            'GPhoto' => 0,
            'OneDrive' => 0,
            'Yandex' => 0,
            'Direct' => 0,
        ];

        if(!empty($links) && is_array($links))
        {
            foreach($links as $l)
            {
                
                if(array_key_exists($l['type'], $resp))
                {
                    $resp[$l['type']] = number_format($l['c']);
                }   
            }
        }

        return $resp;


    }

    public function getRDT()
    {
        $links =  $this->db->rawQuery('SELECT status, count(1) as c From links Group by status');

        $resp = [
            'active' => 0,
            'inactive' => 0,
            'broken' => 0
        ];

        if(!empty($links) && is_array($links))
        {
            foreach($links as $l)
            {
                switch($l['status'])
                {
                    case '0':
                        $resp['active'] = number_format($l['c']);
                    break;

                    case '1':
                        $resp['inactive'] = number_format($l['c']);
                    break;

                    case '2':
                        $resp['broken'] = number_format($l['c']);
                    break;
                }

            }
        }

        return $resp;


    }

    protected function s($u)
    {
        if($this->isEdit() && Helper::isDrive($u))
        {
            if($this->obj['type'] == 'GDrive')
            {
                $o_gid = Helper::getDriveId($this->obj['main_link']);
                $n_gid = Helper::getDriveId($u);
                if($o_gid != $n_gid)
                {
                    $this->t = true;
                }
            }
        }
        else
        {
            if(!$this->isEdit())
            {
                $this->t = true;
            }
        }


    }


    public function getTotalLinks() {
        $this->db->get($this->tbl);
        return $this->db->count;
    }

    public function getTotalViews() {
        $stats = $this->db->getOne($this->tbl, "sum(views)");
        return (isset($stats['sum(views)'])) ? $stats['sum(views)'] : 0;
    }

    public function getMostViewed() {
        $this->db->where("status", 2, "!=");
        $this->db->orderBy("views", "desc");
        $results = $this->db->get($this->tbl, 10);
        if ($this->db->count > 0) {
            return $results;
        } else {
            return [];
        }
    }
    public function getRecentlyAdded() {
        $this->db->where("status", 2, "!=");
        $this->db->orderBy("created_at", "desc");
        $results = $this->db->get($this->tbl, 10);
        if ($this->db->count > 0) {
            return $results;
        } else {
            return [];
        }
    }





}