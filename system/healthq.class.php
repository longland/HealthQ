<?php
	class healthQ {
		public $version = 0.8;
		
		public function __construct($routine, $data, $settings) {
			$this->settings = parse_ini_file($settings, true);
			//$this->db = $this->connectToDatabase();
			$this->generateReplaceableValues();
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

		private function uuid() {
			return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
				mt_rand( 0, 0xffff ),
				mt_rand( 0, 0x0fff ) | 0x4000,
				mt_rand( 0, 0x3fff ) | 0x8000,
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
			);
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
		
		private function generateReplaceableValues() {
			if (!$this->db) {
				$this->display(true, "index", "There has been an error with the database. Please try again later.");
			}
			$this->settings['replace']['<siteurl>'] = "http://" . $_SERVER["HTTP_HOST"];
			if ($this->isLoggedIn()) {
				$result = $this->db->query("SELECT name FROM users WHERE id='" . $this->user . "';");
				$result = $result->fetch_assoc();
				$this->settings['replace']['<username>'] = $result['name'];
			} else {
				$this->settings['replace']['<username>'] = 'guest';
			}
			$this->settings['replace']['`'] = '\'';
		}
		
		private function display($template, $data, $error=NULL) {
			$data["error"] = $error;
			require_once $this->settings['layout']['template'];
			die();
		}
	}
?>
