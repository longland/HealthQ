<?php
	class healthQ {
		public $version = 0.8;
		
		public function __construct($routine, $data, $settings) {
			$this->settings = parse_ini_file($settings, true);
			//$this->db = $this->connectToDatabase();
			//$this->loggedin = false;
			//$this->user = 0;
			$this->timeStarted = microtime(true);
			
			$this->db = new mysqli($this->settings["db"]["host"], $this->settings["db"]["user"], $this->settings["db"]["pass"], $this->settings["db"]["name"]);
			
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
			$profile_id = $_COOKIE["fbs_175392325877131"];
			$profile_id = explode("&",$profile_id);
			$profile_id = explode("=",$profile_id[6]);
			$profile_id = $profile_id[1];
			
			$isnewuser = $this->db->query("SELECT USER_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			if (!$isnewuser = $isnewuser->fetch_assoc();) {
				$noqs = $this->db->query("SELECT QUESTION FROM QUESTIONS");
				$noqs = $noqs->num_rows - 1;
				$startpoint = mt_rand(0,$noqs);
				$this->db->query("INSERT INTO USER (PROFILE_ID, Q_ID) VALUES (" . $profile_id . " , " . $startpoint . ")");
			}
			
			require_once "dashboard.php";
			die();
		}
		
		public function crime($argument) {
			//require_once "login.php";
			
			$crimeresult = $this->db->query("SELECT USER_ID, STATUS_ID, TYPE_ID, LAT, LONG, USER_ID WHERE ID = " . (int)$argument[0] . " LIMIT 1;");
			$crimeresult = $crimeresult->fetch_assoc();
			$statusresult = $this->db->query("SELECT TYPE_NAME FROM CRIME_TYPE WHERE TYPE_ID = " . (int)$crimeresult["TYPE_ID"] . " LIMIT 1;";
			$statusresult = $statusresult->fetch_assoc();
			$profile_id = $_COOKIE["fbs_175392325877131"];
			$profile_id = explode("&",$profile_id);
			$profile_id = explode("=",$profile_id[6]);
			$profile_id = $profile_id[1];
			$questionresult = $this->db->query("SELECT USER_ID, Q_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			$questionresult = $questionresult->fetch_assoc();
			$thequestion = $this->db->query("SELECT QUESTION, OPTION1, OPTION2, OPTION3, OPTION4, ANSWER FROM QUESTIONS WHERE ID = " . (int)$questionresult[""] . " LIMIT 1;");
			$thequestion = $thequestion->fetch_assoc();
			
			$crime = "A " . $statusresult["TYPE_NAME"] . " has occurred! Solve the riddle to solve the crime:";
			$question = $thequestion["QUESTION"];
			$answer[0] = $thequestion["OPTION1"];
			$answer[1] = $thequestion["OPTION2"];
			$answer[2] = $thequestion["OPTION3"];
			$answer[3] = $thequestion["OPTION4"];
			$correct = $thequestion["ANSWER"]-1;
			
			$data["head"]  = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# crimesapp: http://ogp.me/ns/fb/crimesapp#">';
			$data["head"] .= '<meta property="fb:app_id"          content="175392325877131">';
			$data["head"] .= '<meta property="og:type"            content="crimesapp:crime"> ';
			$data["head"] .= '<meta property="og:url"             content="http://health.itza.uk.com/crime/0/"> ';
			$data["head"] .= '<meta property="og:title"           content="Sample Crime"> ';
			$data["head"] .= '<meta property="og:description"     content="Some Arbitrary String"> ';
			$data["head"] .= '<meta property="og:image"           content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png"> ';
			$data["head"] .= '<meta property="crimesapp:question" content="' . $crime . " " . $question . '"> ';
			$data["head"] .= '<meta property="crimesapp:answer"   content="' . $answer[$correct] . '"> ';
			
			require_once "header.php";
			echo "<p>There has been a crime!<br />" . $crime . "<br />" . $question . "<br /><br />";
			for ($i = 0; $i > 3; $i++) {
				echo "<a href='http://health.itza.uk.com/answer/" . (int)$argument[0] . "/" . $i . "' href='#'>" . $answer[$i] . "</a><br />";
			}
			echo "</p>";
			require_once "footer.php";
			die();
		}
		
		public function answer($args) {
			$crimeresult = $this->db->query("SELECT USER_ID, STATUS_ID, TYPE_ID, LAT, LONG, USER_ID WHERE ID = " . (int)$argument[0] . " LIMIT 1;");
			$crimeresult = $crimeresult->fetch_assoc();
			$statusresult = $this->db->query("SELECT TYPE_NAME FROM CRIME_TYPE WHERE TYPE_ID = " . (int)$crimeresult["TYPE_ID"] . " LIMIT 1;";
			$statusresult = $statusresult->fetch_assoc();
			$profile_id = $_COOKIE["fbs_175392325877131"];
			$profile_id = explode("&",$profile_id);
			$profile_id = explode("=",$profile_id[6]);
			$profile_id = $profile_id[1];
			$questionresult = $this->db->query("SELECT USER_ID, Q_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			$questionresult = $questionresult->fetch_assoc();
			$thequestion = $this->db->query("SELECT ANSWER FROM QUESTIONS WHERE ID = " . (int)$questionresult[""] . " LIMIT 1;");
			$thequestion = $thequestion->fetch_assoc();
			if ($args[1] == $thequestion["ANSWER"]) {
				// Update to Solved
				header("Location: http://health.itza.uk.com/welldone/");
			} else {
				// Update to Unsolved, remove user
				header("Location: http://health.itza.uk.com/muppet/");
			}
		}
		
		public function saveuser($args) {
			$userid = $args[0];
		}
		
		public function welldone($args) {
			require_once "header.php";
			require_once "welldone.php";
			require_once "footer.php";
			die();
		}
		
		public function recommend($args) {
			require_once "header.php";
			require_once "recommend.php";
			require_once "footer.php";
			die();
		}
		
		public function muppet($args) {
			require_once "header.php";
			require_once "muppet.php";
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
