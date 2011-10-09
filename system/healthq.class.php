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
			require_once "header.php";
			require_once "login.php";
			//$data["title"] = "Home";
			//$data["body"] = "<p>Are you happy now?</p>";
			require_once "footer.php";
			die();
		}
		
		public function dashboard($argument) {
			require_once "dashboard.php";
			//$data["title"] = "Dashboard";
			//$data["body"] = "<p>This is the dashboard. Fear it's wrath!</p>";
			//require_once "footer.php";
			die();
		}
		
		public function crime($argument) {
			//require_once "login.php";
			$crime = "There has been a kidnapping! The kidnapper left a note, solve the riddle to solve the crime:";
			$question = "Where did Sherlock Holmes live?";
			$answer[0] = "221b Baker Street";
			$answer[1] = "92 Picadilly Road";
			$answer[2] = "314d Derby Lane";
			$correct = 0;
			$data["head"]  = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# crimesapp: http://ogp.me/ns/fb/crimesapp#">';
			$data["head"] .= '<meta property="fb:app_id"          content="175392325877131">';
			$data["head"] .= '<meta property="og:type"            content="crimesapp:crime"> ';
			$data["head"] .= '<meta property="og:url"             content="http://health.itza.uk.com/crime/0/"> ';
			$data["head"] .= '<meta property="og:title"           content="Sample Crime"> ';
			$data["head"] .= '<meta property="og:description"     content="Some Arbitrary String"> ';
			$data["head"] .= '<meta property="og:image"           content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png"> ';
			$data["head"] .= '<meta property="crimesapp:question" content="' . $crime . " " . $question . '"> ';
			$data["head"] .= '<meta property="crimesapp:answer"   content="' . $answer[$correct] . '"> ';
			$data["title"] = "There Has Been A Crime!";
			$data["body"]  = "<p>There has been a crime!<br />" . $crime . "<br />" . $question . "<br /><br />";
			$data["body"] .= "<a onclick=" . "</p>";
			require_once "footer.php";
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
