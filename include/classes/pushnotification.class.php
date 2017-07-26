<?php
	$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

	class PushNotification extends Base {
		var $tableSettings = 'push_notification_settings';
		
		private static function getClassesInFile($file){
			$classes = array();
			$tokens = token_get_all(file_get_contents($file));
			$count = count($tokens);
			for ($i = 2; $i < $count; $i++) {
				if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
					$class_name = $tokens[$i][1];
					$classes[] = $class_name;
				}
			}
			return $classes;
		}
		
		private static $classes = null;
		public function getClasses(){
			if (self::$classes === null){
				$directory = new DirectoryIterator(__DIR__.'/push_notification');
				foreach ($directory as $fileInfo) {
					if (($fileInfo->getExtension() != 'php') || $fileInfo->isDot()) {
						continue;
					}
					foreach (self::getClassesInFile($fileInfo->getRealPath()) as $class){
						if (!class_exists($class)){
							include $fileInfo->getRealPath();
						}
						$cr = new ReflectionClass($class);
						if ($cr->isSubclassOf('IPushNotification')){
							self::$classes[$class] = array($fileInfo->getFilename(), $cr->getMethod('getName')->invoke(null), $cr->getMethod('getParameters')->invoke(null));
						}
					}
				}
			}
			return self::$classes;
		}
		
		public function getClassesForSmarty(){
			$c = $this->getClasses();
			return array_map(function($a, $b){
				return array(
					'class' => $b,
					'file' => $a[0],
					'name' => $a[1],
					'parameters' => $a[2],
				);
			}, $c, array_keys($c));
		}
		
		/**
		 * @param string|array $notificator
		 * @param array $data
		 * @return IPushNotification|bool
		 */
		public function getNotificatorInstance($notificator, $data){
			$class = null;
			$file = null;
			
			if (is_array($notificator)){
				if (count($notificator) == 2){
					list($class, $file) = $notificator;
				} else {
					$class = reset($notificator);
				}
			} else {
				$class = $notificator;
			}
			
			if (!class_exists($class)){
				if ($file === null){
					foreach (self::getClasses() as $_class => $_info){
						if ($_class == $class){
							$file = $_info[0];
							break;
						}
					}
				} else {
					include __DIR__.'/push_notification/'.$file;
				}
				if (!class_exists($class)){
					return false;
				}
			}
			$cr = new ReflectionClass($class);
			$constructor = $cr->getConstructor();
			$constructorParameters = array();
			foreach (array_map(function($a){ return $a->getName();}, $constructor->getParameters()) as $param){
				$constructorParameters[] = array_key_exists($param, $data)?$data[$param]:null;
			}
			$instance = $cr->newInstanceArgs($constructorParameters);
			return $instance;
		}
		
		/**
		 * Update accounts push notification settings
		 * @param account_id int Account ID
		 * @param data array Data array
		 * @return bool
		 **/
		public function updateSettings($account_id, $data) {
			UserSettings::construct($account_id)->PushNotifications = $data;
			return true;
		}
		
		/**
		 * Fetch notification settings for user account
		 * @param id int Account ID
		 * @return array Notification settings
		 **/
		public function getNotificationSettings($account_id) {
			if ($settings = UserSettings::construct($account_id)->PushNotifications){
				return $settings;
			}
			return array(
				'class' => false,
				'params' => null,
				'file' => null,
			);
		}
		
		private static $instance = null;
		/**
		 * @param PushNotification $instance
		 */
		public static function Instance($instance = null){
			if (func_num_args() == 0){
				return self::$instance;
			}
			return self::$instance = $instance;
		}
		
		public function sendNotification($account_id, $template, $aData){
			$settings = $this->getNotificationSettings($account_id);
			if ($settings['class']){
				$instance = $this->getNotificatorInstance(array($settings['class'], $settings['file']), $settings['params']);
				if ($instance){
					$this->smarty->assign('WEBSITENAME', $this->setting->getValue('website_name'));
					$this->smarty->assign('SUBJECT', $aData['subject']);
					$this->smarty->assign('DATA', $aData);
						
					$message = false;
					foreach (array('/mail/push_notifications/', '/mail/notifications/') as $dir){
						$this->smarty->clearCache($templateFile = TEMPLATE_DIR.$dir.$template.'.tpl');
						try {
							$message = $this->smarty->fetch($templateFile);
							break;
						} catch (SmartyException $e){
							
						}
					}
					if ($message){
						$instance->notify($message, 'info', $aData['subject']);
					}
				}
			}
			return true;
		}
	}
	
	$pushnotification = PushNotification::Instance(new PushNotification());
	$pushnotification->setDebug($debug);
	$pushnotification->setLog($log);
	$pushnotification->setMysql($mysqli);
	$pushnotification->setSmarty($smarty);
	$pushnotification->setConfig($config);
	$pushnotification->setSetting($setting);
	$pushnotification->setErrorCodes($aErrorCodes);
