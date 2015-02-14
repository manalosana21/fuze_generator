<?php
abstract class View_Helper_Dataset implements Iterator
{
    protected $m_offset;
    protected $m_data;
    protected $m_keys;
    function __construct(&$data)
    {
        $this->m_keys = array_keys($data);
        $this->m_data = $data;
        $this->m_offset = 0;
     }
    
    function _rebuild_keys()
    {
		//echo "_rebuild_keys"."<BR>";
		$this->m_keys = array_keys($this->m_data);
  	}
    
    private function _pos()
    {
		//echo "_pos"."<BR>";
         return $this->m_offset;
    }
    
    private function _key()
    {
		//echo "_key"."<BR>";
        return $this->m_keys[$this->_pos()];
    }
    
    private function _data()
    {
		//echo "_data"."<BR>";
        return $this->m_data[$this->_key()];
    }
    
    private function _key_offset_valid()
    {
		//echo "_key_offset_valid"."<BR>";
        $result = false;
        if (isset($this->m_keys[$this->_pos()]))
        {
            $result = true;
        }
        return $result;
    }
    
    private function _key_valid($key)
    {
		//echo "_key_valid"."<BR>";
        $result = false;
        if (isset($this->m_data[$key]))
        {
              $result = true;
        }
        return $result;
    }
    
    private function _key_value($key)
    {
		//echo "_key_value"."<BR>";
        return $this->m_data[$key];
    }
    
    private function _remove_data($key)
    {
		//echo "_remove_data"."<BR>";
         unset($this->m_data[$key]);
        $this->_rebuild_keys();
    }
    
    public function current()
    {
		//echo "current"."<BR>";
        return $this->_data();
    }
    
    public function key()
    {
		//echo "key"."<BR>";
         return $this->_key();
    }
    
    public function next()
    {
		//echo "next"."<BR>";
        $this->m_offset++;
    }
    
    public function rewind()
    {
		//echo "rewind"."<BR>";
        $this->m_offset=0;
    }
    
    public function valid()
    {
		//echo "valid"."<BR>";
        return $this->_key_offset_valid();
    }
    
    public function __get($name)
    {
        $result=null;
        if ($this->_key_valid($name))
        {
            $result = $this->_key_value($name);
        }  
        return $result;
    }
    
    public function __set($name,$value)
    {
		//echo "<BR>".$name."__set"."<BR>";
        $this->m_data[$name] = $value;
		
        $this->_rebuild_keys();
    }
    
    public function remove($name)
    {
		//echo "remove"."<BR>";
        $this->_remove_data($name);
    }
  
 
	
    public function is_empty($name)
    {
		//echo "__get"."<BR>";
		$is_valid = true;	
        $result=null;
        if ($this->_key_valid($name))
        {
            $result = $this->_key_value($name);
			if(!empty($result)) {
				$is_valid = false;
			}
        }
        return $is_valid;
    } 
}

?>