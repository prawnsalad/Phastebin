<?php

	namespace Phastebin\Stores;

	class File {
		// Directory to store the content files (relative to public folder)
		private $base_path = '../data/';

		public function set($key, $content) {
			$filename = $this->base_path . $this->generateFilename($key);

			$save = file_put_contents($filename, $content);
			
			return ($save === false) ? false : $key;
		}


		public function get($key) {
			$filename = $this->base_path . $this->generateFilename($key);

			if (!is_readable($filename))
				return false;
			
			return file_get_contents($filename);
		}


		private function generateFilename($key) {
			return md5($key);
		}
	}