<?php

	namespace Phastebin\KeyGenerators;

	class Random {
		private $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		public function createKey($key_length=null) {
			if (!$key_length)
				$key_length = 8;

			$key = '';

			for($i=0; $i<$key_length; $i++) {
				// Grab a random character
				$key .= $this->chars[rand(0, strlen($this->chars) - 1)];
			}

			return $key;
		}
	}