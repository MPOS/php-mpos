<?php
	interface IPushNotification {
		public static function getName();
		public static function getParameters();
		public function notify($message, $severity, $event);
	}