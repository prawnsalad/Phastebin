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
				$store = new Stores\File();
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
				$store = new Stores\File();
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
				$store = new Stores\File();
				$saved_key = $store->set($app->request()->getBody());

				$res = $app->response();
				$res['Content-Type'] = 'application/json';

				if (!$saved_key) {
					$res->body(json_encode(array(
						'message' => 'Error adding document.'
					)));

					$res->status(500);
					return;
				}

				$res->body(json_encode(array(
					'key' => $saved_key
				)));
			});


			/**
			 * Serving up the index page
			 */
			$app->get('/(:id)', function() use ($app) {
				readfile('index.html');
			});
		}
	}