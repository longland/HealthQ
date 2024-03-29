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
			
			if ($profile_id == "") {
				header("Location: http://health.itza.uk.com/");
				die();
			}
			
			$profile_id = explode("&uid=",$profile_id);
			$profile_id = $profile_id[1];
			$profile_id = substr($profile_id, 0, strlen($profile_id)-2);
			
			$isnewuser = $this->db->query("SELECT USER_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			if (!($isnewuser = $isnewuser->fetch_assoc())) {
				$noqs = $this->db->query("SELECT QUESTION FROM QUESTIONS");
				$noqs = $noqs->num_rows;
				$startpoint = mt_rand(1,$noqs);
				$this->db->query("INSERT INTO USER (PROFILE_ID, QUESTION_ID) VALUES (" . $profile_id . " , " . $startpoint . ")");
			}
			$uid = $this->db->query("SELECT USER_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			$uid = $uid->fetch_assoc();
			$uid = $uid["USER_ID"];
						
			$crimeresult = $this->db->query("SELECT CRIME_ID, STATUS_ID, TYPE_ID, CRIME_LAT, CRIME_LONG, USER_ID FROM CRIME WHERE USER_ID = " . $uid . " AND STATUS_ID = 1 LIMIT 1;");
			if ($crimeresult = $crimeresult->fetch_assoc()) {
				$data["type"] = $crimeresult["TYPE_ID"];
				$data["action"] = $crimeresult["CRIME_ID"];
			} else {
				$crimeresult = $this->db->query("SELECT CRIME_ID, STATUS_ID, TYPE_ID, CRIME_LAT, CRIME_LONG, USER_ID FROM CRIME WHERE STATUS_ID = 0 LIMIT 1;");
				$crimeresult = $crimeresult->fetch_assoc();
				$data["type"] = $crimeresult["TYPE_ID"];
				$data["action"] = $crimeresult["CRIME_ID"];
				$this->db->query("UPDATE CRIME SET STATUS = 1 AND USER_ID = " . $uid . " WHERE CRIME_ID = " . $crimeresult["CRIME_ID"]);
			}
					
			require_once "dashboard.php";
			die();
		}
		
		public function crime($argument) {
			//require_once "login.php";
			
			$crimeresult = $this->db->query("SELECT CRIME_ID, STATUS_ID, TYPE_ID, CRIME_LAT, CRIME_LONG, USER_ID FROM CRIME WHERE CRIME_ID = " . (int)$argument[0] . " LIMIT 1;");
			$crimeresult = $crimeresult->fetch_assoc();
			$statusresult = $this->db->query("SELECT TYPE_NAME FROM CRIME_TYPES WHERE TYPE_ID = " . (int)$crimeresult["TYPE_ID"] . " LIMIT 1;");
			$statusresult = $statusresult->fetch_assoc();
			$profile_id = $_COOKIE["fbs_175392325877131"];			
			if ($profile_id == "") {
				header("Location: http://health.itza.uk.com/");
				die();
			}
			$profile_id = explode("&uid=",$profile_id);
			$profile_id = $profile_id[1];
			$profile_id = substr($profile_id, 0, strlen($profile_id)-2);
			$questionresult = $this->db->query("SELECT USER_ID, QUESTION_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			$questionresult = $questionresult->fetch_assoc();
			$thequestion = $this->db->query("SELECT QUESTION, OPTION_1, OPTION_2, OPTION_3, OPTION_4, ANSWER FROM QUESTIONS WHERE QUESTION_ID = " . (int)$questionresult["QUESTION_ID"] . " LIMIT 1;");
			$thequestion = $thequestion->fetch_assoc();
			
			$crime = "A " . $statusresult["TYPE_NAME"] . " has occurred! Solve the riddle to solve the crime:";
			$question = $thequestion["QUESTION"];
			$answer[0] = $thequestion["OPTION_1"];
			$answer[1] = $thequestion["OPTION_2"];
			$answer[2] = $thequestion["OPTION_3"];
			$answer[3] = $thequestion["OPTION_4"];
			$correct = $thequestion["ANSWER"]-1;
			
			$data["meta"]  = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# crimesapp: http://ogp.me/ns/fb/crimesapp#"';
			$data["head"] .= '<meta property="fb:app_id"          content="175392325877131">';
			$data["head"] .= '<meta property="og:type"            content="crimesapp:crime"> ';
			$data["head"] .= '<meta property="og:url"             content="http://health.itza.uk.com/"> ';
			$data["head"] .= '<meta property="og:title"           content="A Heinious Crime"> ';
			$data["head"] .= '<meta property="og:description"     content="Some Arbitrary String"> ';
			$data["head"] .= '<meta property="og:image"           content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png"> ';
			$data["head"] .= '<meta property="crimesapp:question" content="' . $crime . " " . $question . '"> ';
			$data["head"] .= '<meta property="crimesapp:answer"   content="' . $answer[$correct] . '"> ';
			
			require_once "qheader.php";
			echo "<p><span class='crime'>There has been a crime!<br />" . $crime . "</span><br /><h1 class='question'>" . $question . "</h1><br /><br />";
			for ($i = 0; $i <= 3; $i++) {
				echo "<a class='answer-";
				echo $i+1;
				echo "' href='http://health.itza.uk.com/answer/";
				echo (int)$argument[0];
				echo "/";
				echo $i+1;
				echo "'>";
				echo $answer[$i];
				echo "</a><br />";
			}
			echo "</p>";
			require_once "qfooter.php";
			die();
		}
		
		public function answer($args) {
			$profile_id = $_COOKIE["fbs_175392325877131"];			
			if ($profile_id == "") {
				header("Location: http://health.itza.uk.com/");
				die();
			}
			$profile_id = explode("&uid=",$profile_id);
			$profile_id = $profile_id[1];
			$profile_id = substr($profile_id, 0, strlen($profile_id)-2);
			$questionresult = $this->db->query("SELECT USER_ID, QUESTION_ID FROM USER WHERE PROFILE_ID = " . $profile_id . " LIMIT 1;");
			$questionresult = $questionresult->fetch_assoc();
			$thequestion = $this->db->query("SELECT ANSWER FROM QUESTIONS WHERE QUESTION_ID = " . (int)$questionresult["QUESTION_ID"] . " LIMIT 1;");
			$thequestion = $thequestion->fetch_assoc();
			if ($args[1] == $thequestion["ANSWER"]) {
				$this->db->query("UPDATE CRIME SET STATUS_ID=2 WHERE CRIME_ID=" . (int)$args[0] . ";");
				$this->db->query("UPDATE USER SET QUESTION_ID = QUESTION_ID+1 WHERE USER_ID = " . $questionresult["USER_ID"] . ";");
				$ch = curl_init("");
				
				$ch = curl_init("https://graph.facebook.com/me/crimesapp:solve");
				curl_setopt($ch, CURLOPT_POSTFIELDS, 'access_token=AAACfhLVHvYsBABiOiTSOSyx8uw3xiKzTw78uk2youpV2JhZCOTJcrooUCEEIy8ZBap2u3jyYCZAv3kab5s5LRMy7OmNbKozJMaQIAcZB5lGHbXUKcaaA');
				curl_setopt($ch, CURLOPT_POSTFIELDS, 'crime=http://samples.ogp.me/175392472543783');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_exec($ch);
				header("Location: http://health.itza.uk.com/welldone/");
			} else {
				$this->db->query("UPDATE CRIME SET STATUS_ID=0 SET USER_ID=NULL WHERE CRIME_ID=" . (int)$args[0] . ";");
				$this->db->query("UPDATE USER SET QUESTION_ID = QUESTION_ID+1 WHERE USER_ID = " . $questionresult["USER_ID"] . ";");
				$this->db->query("UPDATE USER SET FAILURES = FAILURES+1 WHERE USER_ID = " . $questionresult["USER_ID"] . ";");
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
