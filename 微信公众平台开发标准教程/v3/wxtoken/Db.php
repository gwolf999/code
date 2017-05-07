<?php
class Db
{
	private $link;
	static private $_instance;
	// 连接数据库
	private function __construct($host, $username, $password)
	{
		$this->link = mysql_connect($host, $username, $password);
		$this->query("SET NAMES 'utf8'", $this->link);
		return $this->link;
	}

	private function __clone(){}

	public static function getMySQLconnect($host, $username, $password)
	{
		if( FALSE == (self::$_instance instanceof self) )
		{
			self::$_instance = new self($host, $username, $password);
		}
		return self::$_instance;
    }

	// 连接数据表
	public function selectDb($database)
	{
		$this->result = mysql_select_db($database);
		return $this->result;
	}

	// 执行SQL语句
	public function query($query)
	{
		return $this->result = mysql_query($query, $this->link);
	}

	// 将结果集保存为数组
	public function fetch_array($fetch_array)
	{
		return $this->result = mysql_fetch_array($fetch_array, MYSQL_ASSOC);
	}

	// 关闭数据库连接
	public function close()
	{
		return $this->result = mysql_close($this->link);
	}

}
?>
