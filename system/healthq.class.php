<?php
	class healthQ {
		public $version = 0.8;
		
		public function __construct($routine, $data, $settings) {
			$this->settings = parse_ini_file($settings, true);
			//$this->db = $this->connectToDatabase();
			//$this->loggedin = false;
			//$this->user = 0;
			$this->timeStarted = microtime(true);
			
			$routine = trim($routine);
			if ($routine != "" and method_exists($this, $routine)) {
				$refl = new ReflectionMethod($this, $routine);
				if ($refl->isPublic()) {
					$this->$routine($data);
				} else {
					$this->error(404);
				}
			} else if ($routine != "" and !method_exists($this, $routine)) {
				$this->error(404);
			} else {
				$this->index($data);
			}
		}
		
		public function index($argument) {
			$pagedata["title"] = "Home";
			$pagedata["body"] = "<p>This is a working test page.</p>";
			$this->display(false, $pagedata);
		}
		
		private function error($errortype) {
			switch ($errortype) {
				case 301:
					header('HTTP/1.1 301 Moved Permanently');
					break;
				case 403:
					header('HTTP/1.1 403 Forbidden');
					$this->display(true, "403");
					break;
				case 404:
					header('HTTP/1.1 404 Not Found');
					$this->display(true, "404");
					break;
			}
		}
		
		private function display($template, $data, $error=NULL) {
			$data["error"] = $error;
			require_once $this->settings['layout']['template'];
			die();
		}
	}
?>
