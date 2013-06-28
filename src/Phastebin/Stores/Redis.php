<?php

	namespace Phastebin\Stores;

	class Redis {
		public function __construct($config) {
			if ($config && isset($config['connection'])) {
				$this->redis = new \Predis\Client($config['connection']);
			} else {
				$this->redis = new \Predis\Client();
			}
		}

		public function set($key, $content) {
			$keyname = $this->generateKeyname($key);
			
			return $this->redis->set($keyname, $content);
		}


		public function get($key) {
			$keyname = $this->generateKeyname($key);

			return $this->redis->get($keyname);
		}


		private function generateKeyname($key) {
			return 'docs.' . $key;
		}
	}