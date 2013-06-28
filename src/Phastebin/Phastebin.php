<?php

	namespace Phastebin;

	class Phastebin {
		private $app;

		public function __construct($app) {
			$this->app = $app;

			$this->addRoutes();
		}


		private function addRoutes() {
			$app = $this->app;

			/**
			 * Reading a raw document
			 */
			$app->get('/raw/:key', function($key) use ($app) {
				$store = $this->getStoreInstance();
				$document_content = $store->get($key);

				$res = $app->response();

				// If the document isn't found, return a JSON error
				if ($document_content === false) {
					$res['Content-Type'] = 'application/json';
					$res->body(json_encode(array(
						'message' => 'Document not found.'
					)));

					$res->status(404);
					return;
				}

				$res['Content-Type'] = 'text/plain';
				$res->body($document_content);
			});


			/**
			 * Reading a document + meta data
			 */
			$app->get('/documents/:key', function($key) use ($app) {
				$store = $this->getStoreInstance();
				$document_content = $store->get($key);

				$res = $app->response();
				$res['Content-Type'] = 'application/json';

				// If the document isn't found, return a JSON error
				if ($document_content === false) {	
					$res->body(json_encode(array(
						'message' => 'Document not found.'
					)));

					$res->status(404);

					return;
				}

				$res->body(json_encode(array(
					'key' => $key,
					'data' => $document_content
				)));
			});


			/**
			 * Saving documents
			 */
			$app->post('/documents', function() use ($app) {
				$key_gen = $this->getkeyGenInstance();
				$key = $key_gen->createKey();

				$store = $this->getStoreInstance();
				$doc_saved = $store->set($key, $app->request()->getBody());

				$res = $app->response();
				$res['Content-Type'] = 'application/json';

				if (!$doc_saved) {
					$res->body(json_encode(array(
						'message' => 'Error adding document.'
					)));

					$res->status(500);
					return;
				}

				$res->body(json_encode(array(
					'key' => $key
				)));
			});


			/**
			 * Serving up the index page
			 */
			$app->get('/(:id)', function() use ($app) {
				readfile('index.html');
			});
		}



		private function getStoreInstance($type=null) {
			if (!$type)
				$type = $this->app->config('store_type');

			$store_class = '\\Phastebin\\Stores\\' . ucfirst($type);
			$store_config = 'store_' . strtolower($type) . '_config';
			return new $store_class($this->app->config($store_config));
		}


		private function getkeyGenInstance($type=null) {
			if (!$type)
				$type = $this->app->config('keygen_type');

			$store_class = '\\Phastebin\\KeyGenerators\\' . ucfirst($type);
			return new $store_class();
		}
	}