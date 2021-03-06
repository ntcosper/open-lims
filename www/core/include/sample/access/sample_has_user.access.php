<?php
/**
 * @package sample
 * @version 0.4.0.0
 * @author Roman Konertz <konertz@open-lims.org>
 * @copyright (c) 2008-2016 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Sample Has User Access Class
 * @package sample
 */
class SampleHasUser_Access
{
	const SAMPLE_HAS_USER_PK_SEQUENCE = 'core_sample_has_users_primary_key_seq';

	private $primary_key;

	private $sample_id;
	private $user_id;
	private $read;
	private $write;
	
	/**
	 * @param integer $primary_key
	 */
	function __construct($primary_key)
	{
		global $db;
			
		if ($primary_key == null)
		{
			$this->primary_key = null;
		}
		else
		{
			$sql = "SELECT * FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $primary_key, PDO::PARAM_INT);
			$db->execute($res);	
			$data = $db->fetch($res);
			
			if ($data['primary_key'])
			{
				$this->primary_key 		= $primary_key;
				
				$this->sample_id		= $data['sample_id'];
				$this->user_id			= $data['user_id'];
				$this->read				= $data['read'];
				$this->write			= $data['write'];
			}
			else
			{
				$this->primary_key = null;
			}
		}
	}
	
	function __destruct()
	{
		if ($this->primary_key)
		{
			unset($this->primary_key);
			
			unset($this->sample_id);
			unset($this->user_id);
			unset($this->read);
			unset($this->write);
		}
	}
	
	/**
	 * @param integer $sample_id
	 * @param integer $user_id
	 * @param bool $read
	 * @param bool $write
	 * @return integer
	 */
	public function create($sample_id, $user_id, $read, $write)
	{
		global $db;
		
		if (is_numeric($sample_id) and is_numeric($user_id) and isset($read) and isset($write))
		{			
			$sql_write = "INSERT INTO ".constant("SAMPLE_HAS_USER_TABLE")." (primary_key,sample_id,user_id,read,write) " .
					"VALUES (nextval('".self::SAMPLE_HAS_USER_PK_SEQUENCE."'::regclass), :sample_id, :user_id, :read, :write)";
			$res_write = $db->prepare($sql_write);
			$db->bind_value($res_write, ":sample_id", $sample_id, PDO::PARAM_INT);
			$db->bind_value($res_write, ":user_id", $user_id, PDO::PARAM_INT);
			$db->bind_value($res_write, ":read", $read, PDO::PARAM_BOOL);
			$db->bind_value($res_write, ":write", $write, PDO::PARAM_BOOL);
			$db->execute($res_write);
			
			if ($db->row_count($res_write) == 1)
			{
				$sql_read = "SELECT primary_key FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE primary_key = currval('".self::SAMPLE_HAS_USER_PK_SEQUENCE."'::regclass)";
				$res_read = $db->prepare($sql_read);
				$db->execute($res_read);
				$data_read = $db->fetch($res_read);
				
				self::__construct($data_read['primary_key']);
				
				return $data_read['primary_key'];
			}
			else
			{
				return null;
			}	
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function delete()
	{
		global $db;
		
		if ($this->primary_key)
		{
			$tmp_primary_key = $this->primary_key;
			
			$this->__destruct();
						
			$sql = "DELETE FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $tmp_primary_key, PDO::PARAM_INT);
			$db->execute($res);
			
			if ($db->row_count($res) == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_sample_id()
	{
		if ($this->sample_id)
		{
			return $this->sample_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_user_id()
	{
		if ($this->user_id)
		{
			return $this->user_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function get_read()
	{
		if (isset($this->read))
		{
			return $this->read;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public function get_write()
	{
		if (isset($this->write))
		{
			return $this->write;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param integer $sample_id
	 * @return bool
	 */
	public function set_sample_id($sample_id)
	{
		global $db;
			
		if ($this->primary_key and is_numeric($sample_id))
		{
			$sql = "UPDATE ".constant("SAMPLE_HAS_USER_TABLE")." SET sample_id = :sample_id WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $this->primary_key, PDO::PARAM_INT);
			$db->bind_value($res, ":sample_id", $sample_id, PDO::PARAM_INT);
			$db->execute($res);
			
			if ($db->row_count($res))
			{
				$this->sample_id = $sample_id;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}	
	}
	
	/**
	 * @param integer $user_id
	 * @return bool
	 */
	public function set_user_id($user_id)
	{
		global $db;

		if ($this->primary_key and is_numeric($user_id))
		{
			$sql = "UPDATE ".constant("SAMPLE_HAS_USER_TABLE")." SET user_id = :user_id WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $this->primary_key, PDO::PARAM_INT);
			$db->bind_value($res, ":user_id", $user_id, PDO::PARAM_INT);
			$db->execute($res);
			
			if ($db->row_count($res))
			{
				$this->user_id = $user_id;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}	
	}
	
	/**
	 * @param bool $read
	 * @return bool
	 */
	public function set_read($read)
	{
		global $db;

		if ($this->primary_key and isset($read))
		{			
			$sql = "UPDATE ".constant("SAMPLE_HAS_USER_TABLE")." SET read = :read WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $this->primary_key, PDO::PARAM_INT);
			$db->bind_value($res, ":read", $read, PDO::PARAM_BOOL);
			$db->execute($res);
			
			if ($db->row_count($res))
			{
				$this->read = $read;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}		
	}
	
	/**
	 * @param bool $write
	 * @return bool
	 */
	public function set_write($write)
	{
		global $db;
	
		if ($this->primary_key and isset($write))
		{			
			$sql = "UPDATE ".constant("SAMPLE_HAS_USER_TABLE")." SET write = :write WHERE primary_key = :primary_key";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":primary_key", $this->primary_key, PDO::PARAM_INT);
			$db->bind_value($res, ":write", $write, PDO::PARAM_INT);
			$db->execute($res);
			
			if ($db->row_count($res))
			{
				$this->write = $write;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * @param integer $sample_id
	 * @param integer $user_id
	 * @return array
	 */
	public static function get_entry_by_sample_id_and_user_id($sample_id, $user_id)
	{
		global $db;
			
		if (is_numeric($sample_id) and is_numeric($user_id))
		{
			$return_array = array();
			
			$sql = "SELECT primary_key FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE sample_id = :sample_id AND user_id = :user_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":sample_id", $sample_id, PDO::PARAM_INT);
			$db->bind_value($res, ":user_id", $user_id, PDO::PARAM_INT);
			$db->execute($res);
			$data = $db->fetch($res);

			if ($data['primary_key'])
			{
				return $data['primary_key'];
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $sample_id
	 * @return array
	 */
	public static function list_entries_by_sample_id($sample_id)
	{
		global $db;
			
		if (is_numeric($sample_id))
		{
			$return_array = array();
			
			$sql = "SELECT primary_key FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE sample_id = :sample_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":sample_id", $sample_id, PDO::PARAM_INT);
			$db->execute($res);
			
			while ($data = $db->fetch($res))
			{
				array_push($return_array,$data['primary_key']);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $user_id
	 * @return array
	 */
	public static function list_users_by_sample_id($sample_id)
	{
		global $db;
			
		if (is_numeric($sample_id))
		{
			$return_array = array();
			
			$sql = "SELECT DISTINCT user_id FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE sample_id = :sample_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":sample_id", $sample_id, PDO::PARAM_INT);
			$db->execute($res);
			
			while ($data = $db->fetch($res))
			{
				array_push($return_array,$data['user_id']);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $user_id
	 * @return array
	 */
	public static function list_entries_by_user_id($user_id)
	{
		global $db;
			
		if (is_numeric($user_id))
		{
			$return_array = array();
			
			$sql = "SELECT primary_key FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE user_id = :user_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":user_id", $user_id, PDO::PARAM_INT);
			$db->execute($res);
			
			while ($data = $db->fetch($res))
			{
				array_push($return_array,$data['primary_key']);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $user_id
	 * @return array
	 */
	public static function list_samples_by_user_id($user_id)
	{
		global $db;
			
		if (is_numeric($user_id))
		{
			$return_array = array();
			
			$sql = "SELECT DISTINCT sample_id FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE user_id = :user_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":user_id", $user_id, PDO::PARAM_INT);
			$db->execute($res);
			
			while ($data = $db->fetch($res))
			{
				array_push($return_array,$data['sample_id']);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return array
	 */
	public static function list_entries()
	{
		global $db;

		$return_array = array();
		
		$sql = "SELECT primary_key FROM ".constant("SAMPLE_HAS_USER_TABLE")."";
		$res = $db->prepare($sql);
		$db->execute($res);
		
		while ($data = $db->fetch($res))
		{
			array_push($return_array,$data['primary_key']);
		}
		
		if (is_array($return_array))
		{
			return $return_array;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $user_id
	 * @return bool
	 */
	public static function delete_by_user_id($user_id)
	{
		global $db;
		
		if (is_numeric($user_id))
		{
			$sql = "DELETE FROM ".constant("SAMPLE_HAS_USER_TABLE")." WHERE user_id = :user_id";
			$res = $db->prepare($sql);
			$db->bind_value($res, ":user_id", $user_id, PDO::PARAM_INT);
			$db->execute($res);
			$res = $db->db_query($sql);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
?>
