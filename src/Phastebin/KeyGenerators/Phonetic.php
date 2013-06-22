<?php

	namespace Phastebin\keyGenerators;

	class Phonetic {
		private $consonants = 'bcdfghjklmnpqrstvwxy';
		private $vowels = 'aeiou';

		public function createKey($key_length=null) {
			if (!$key_length)
				$key_length = 8;

			$key = '';

			for($i=0; $i<$key_length; $i++) {
				// Alternate between a consonant and a vowel
				$chars = ($i & 1) ? $this->consonants : $this->vowels;

				// Grab a random character
				$key .= $chars[rand(0, strlen($chars) - 1)];
			}

			return $key;
		}
	}