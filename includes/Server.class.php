<?php




class Server
{

    protected $db;
    protected $config;
    protected $obj;
    protected $tbl = 'servers';
    protected $servers;
    protected $error;
    /**
     * Blacklisted columns
     * @since 1.3
     **/    
    protected $blackListed = ['id'];
    


    public function __construct($db, $config)
    {
        $this->db = $db;
        $this->config = $config;
        $this->initProperties();
        $this->init();
    }


    /**
     * Assign data
     * @author CodySeller <https://codyseller.com>
     * @since 1.3
     */
    public function assign($data = [])
    {

        foreach($data as $k => $v)
        {
            if(array_key_exists($k, $this->obj))
            {
                $this->obj[$k] = $v;
            }
        }
        return $this;
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


    protected function beforeSave()
    {
        if(empty($this->obj['playbacks']))
        {
            $this->obj['playbacks'] = 0;
        }
        if(!$this->isEdit())
        {
            $this->obj['status'] = 0;
        }
    }

    protected function init()
    {
        $this->db->where ("is_broken", 0);
        $this->db->where ("status", 0);
        $servers = $this->db->get($this->tbl);
        if($this->db->count > 0)
        {
            foreach($servers as $k => $v)
            {
                $this->servers[$v['id']] = $v;
            }
        }
        else
        {
            $this->servers = [];
        }

    }
    public function getAll($a = false, $c = false)
    {
        if(!$a)
        {
            $this->db->orderBy("id","Desc");
            $servers = $this->db->get($this->tbl);
            if(!$c)
            {
                return $this->db->count > 0 ? $servers : [];
            }
            else
            {
                return $this->db->count;
            }
            
        }
        else
        {
            return $this->servers;
        }

    }


    

    public function load($id)
    {
        if(!is_array($id))
        {
            $server = $this->findById($id);
            if($server)
            {
                foreach($server as $k => $v)
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
        else
        {
            $this->obj = $id;
        }



    }

    /**
     * Get currect server id
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

    public function getOne($id = '')
    {
        $s = false;
        if(!empty($this->servers))
        {
            if(!empty($id))
            {
                if(array_key_exists($id, $this->servers))
                {
                    $s = $this->servers[$id];
                }
            }
            else
            {
                $s = $this->servers[array_rand($this->servers)];
            }
        }


      
        return $s;
    }

    public function del($id)
    {
        $this->db->where('id', $id);
        if( $this->db->delete($this->tbl)){
            return true;
        }
        return false;
    }

    public function isHit()
    {
        if($this->isEdit())
        {
            $resp = Helper::isI($this->obj['domain'] . '/stream/test');
                        
            if($resp == 200)
            {
                if($this->obj['is_broken'] == 1)
                {
                    $this->obj['is_broken'] = 0;
                    $this->save();
                }
                return true;
            }
        }
        return false;
    }





    public function changeStatus()
    {
        if($this->isEdit())
        {
            if($this->obj['status'] == 1)
            {
                $this->obj['status'] = 0;
            }
            else
            {
                $this->obj['status'] = 1;
            }

            $this->save();
        }
    }

    public function addPlayback()
    {
        if($this->isEdit())
        {
           $this->db->where('id', $this->getID());
           $this->db->update($this->tbl, ['playbacks'=>$this->obj['playbacks'] + 1], 1);
        }
    }

   



}