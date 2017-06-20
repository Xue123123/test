<?php
/**
 *  session mysql 数据库存储类
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-8
 */
class session_mysql {
	var $lifetime = 1800;
	var $model;
	var $table;
/**
 * 构造函数
 *
 */
	public function __construct() {
		router::load_model('session_model');
		$this->model = new session_model();
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc'));
		register_shutdown_function('session_write_close');
		session_start();
	}
/**
 * session_set_save_handler  open方法
 * @param $save_path
 * @param $session_name
 * @return true
 */
	public function open($save_path, $session_name) {
		#echo $save_path, $session_name;
		return true;
	}
/**
 * session_set_save_handler  close方法
 * @return bool
 */
	public function close() {
		return $this->gc($this->lifetime);
	}
/**
 * 读取session_id
 * session_set_save_handler  read方法
 * @return string 读取session_id
 */
	public function read($id) {
		#echo "select * from `session` where ssid='$id'" ;
		$r = $this->model->getOne("select * from `session` where ssid='$id'");
		#print_r($r);
		#echo $r['data'];
		return $r ? $r['data'] : '';
	}
/**
 * 写入session_id 的值
 *
 * @param $id session
 * @param $data 值
 * @return mixed query 执行结果
 */
	public function write($id, $data) {
		$sessiondata = array(
			'ssid' => $id,
			'data' => $data,
			'lastvisit' => time(),
		);
		return $this->model->insert($sessiondata, 1);
	}
/**
 * 删除指定的session_id
 *
 * @param $id session
 * @return bool
 */
	public function destroy($id) {
		#echo 'destry!';
		return $this->model->delete($id);
	}
/**
 * 删除过期的 session
 * @param $maxlifetime 存活期时间
 * @return bool
 */
	public function gc($maxlifetime) {

		$exptime = time() - $maxlifetime;
		return $this->model->db->query('delete from `session`  where  `lastvisit`<' . $exptime);
	}
}
?>