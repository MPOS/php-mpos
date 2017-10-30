<?php
	$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;
	
	class UserSettings extends Base {
		protected $table = 'user_settings';
		
		private $__cache = array();
		protected $account_id = null;
		private $__lazyWrite;
		
		public function __construct($account_id, $lazy_write = true){
			$this->account_id = $account_id;
			$this->__lazyWrite = $lazy_write;
			if (is_callable(self::$__setup_callbacks)){
				call_user_func(self::$__setup_callbacks, $this);			
			}
		}
		
		private static $__GetSTMT = null;
		private static $__SetSTMT = null;
		
		public function __destruct(){
			if ($this->__lazyWrite){
				foreach ($this->__cache as $name=>$value){
					$this->_storeValue($name, $value);
				}
			}
		}
		
		private function _storeValue($name, $value){
			if (empty(self::$__SetSTMT)){
				self::$__SetSTMT = $this->mysqli->prepare('REPLACE INTO '.$this->table.' (`account_id`, `name`, `value`) VALUES (?, ?, ?)');
			}
			$val = serialize($value);
			if (!(self::$__SetSTMT && self::$__SetSTMT->bind_param('iss', $this->account_id, $name, $val) && self::$__SetSTMT->execute())) {
				$this->setErrorMessage($this->getErrorMsg('E0084', $this->table));
				return $this->sqlError();
			}
			return true;
		}
		
		private function _getValue($name, $default = null){
			if (empty(self::$__GetSTMT)){
				self::$__GetSTMT = $this->mysqli->prepare('SELECT `value` FROM '.$this->table.' WHERE `account_id` = ? AND `name` = ? LIMIT 1');
			}
			if (self::$__GetSTMT && self::$__GetSTMT->bind_param('is', $this->account_id, $name) && self::$__GetSTMT->execute() && $result = self::$__GetSTMT->get_result()) {
				if ($result->num_rows > 0) {
					return unserialize($result->fetch_object()->value);
				} else {
					return $default;
				}
			}
			$this->sqlError();
			return $default;
		}
		
		public function __get($name){
			if (!$this->__lazyWrite){
				return $this->_getValue($name);
			}
			if (!array_key_exists($name, $this->__cache)){
				$this->__cache[$name] = $this->_getValue($name);
			}
			return $this->__cache[$name];
		}
		
		public function __set($name, $value){
			if (!$this->__lazyWrite){
				$this->_storeValue($name, $value);
			} else {
				$this->__cache[$name] = $value;
			}
		}
	
		private static $__setup_callbacks = null;
		public static function setup($callback = null){
			self::$__setup_callbacks = $callback;
		}
		
		private static $__lastInstanceId;
		private static $__lastInstance;
		/**
		 * @param int $account_id
		 * @param string $lazy_write
		 * @return UserSettings
		 */
		public static function construct($account_id, $lazy_write = true){
			if ((self::$__lastInstanceId == $account_id) && (self::$__lastInstance instanceof UserSettings)){
				return self::$__lastInstance;
			}
			self::$__lastInstanceId = $account_id;
			return self::$__lastInstance = new self($account_id, $lazy_write);
		}
	}
	
	UserSettings::setup(function($instance)use($debug, $log, $mysqli, $aErrorCodes){
		$instance->setDebug($debug);
		$instance->setLog($log);
		$instance->setMysql($mysqli);
		$instance->setErrorCodes($aErrorCodes);
	});
