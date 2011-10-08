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
			require_once "login.php";
			//$data["title"] = "Home";
			//$data["body"] = "<p>Are you happy now?</p>";
			require_once $this->settings['layout']['template'];
			die();
		}
		
		public function dashboard($argument) {
			//require_once "login.php";
			$data["title"] = "Dashboard";
			$data["body"] = "<p>This is the dashboard. Fear it's wrath!</p>";
			require_once $this->settings['layout']['template'];
			die();
		}
		
		private function error($errortype) {
			switch ($errortype) {
				case 301:
					header('HTTP/1.1 301 Moved Permanently');
					break;
				case 403:
					header('HTTP/1.1 403 Forbidden');
					break;
				case 404:
					header('HTTP/1.1 404 Not Found');
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
