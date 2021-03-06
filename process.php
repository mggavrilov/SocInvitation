<?php
	$pageTitle = "Сканиране";
	
	require_once('views/header.php');
	
	if(!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit;
	}
	
	require_once('models/Facebook_model.php');
	require_once('models/Setting_model.php');
	require_once('models/Student_model.php');

	$facebookModel = new Facebook_model();
	$fb = $facebookModel->getFacebookObject();
		
	//Check if there's a valid FB user token
	if(!$facebookModel->isFacebookTokenValid()) {
		$helper = $fb->getRedirectLoginHelper();
		 
		$permissions = ['user_managed_groups', 'groups_access_member_info', 'publish_to_groups'];
		
		$protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");
		$url = "$protocol://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; // http://website.com/x/y/z/index.php
		$url = substr($url, 0, strrpos($url, '/')); // http://website.com/x/y/z
		$url = $url . '/fb-callback.php'; // http://website.com/x/y/z/fb-callback.php
		
		$loginUrl = $helper->getLoginUrl($url, $permissions);

		header("Location: " . $loginUrl);
		exit;
	}
	
	//Check if all mandatory settings are set
	$settingModel = new Setting_model();
	$settings = $settingModel->getAllSettings();
	foreach($settings as $setting) {
		if(empty($setting['value'])) {
			$_SESSION['notification']['message'] = "Някои от задължителните настройки не са попълнени. Моля попълнете ги преди да продължите със сканирането.";
			$_SESSION['notification']['type'] = "error";
			header("Location: settings.php");
			exit;
		}
	}
	
	$studentModel = new Student_model();
	$hasStudents = $studentModel->hasStudents();
	
	if(!empty($_POST) || !empty($_FILES)) {
		require_once('models/Post_model.php');
	
		if(isset($_FILES['directory'])) {
			$folderInvitations = array();
		
			//Get data from selected folder
			foreach($_FILES['directory']['name'] as $invitationName) {
				$folderInvitations[$invitationName] = true;
			}
		}
		
		if(!isset($_POST['scantarget']) || $_POST['scantarget'] == "file") {
			define("MAX_FILE_SIZE", 20000000); //20MB
	
			require_once('helpers/CSVHelper.php');
			
			if(isset($_POST['truncate'])) {
				$studentModel->truncateStudents();
			}
		
			if(!isset($_FILES['file'])) {
				$_SESSION['notification']['message'] = "Не е прикачен CSV файл.";
				$_SESSION['notification']['type'] = "error";
				header("Location: process.php");
				exit;
			}
			
			if($_FILES['file']['error'] != 0) {
				$_SESSION['notification']['message'] = "Възникна грешка при качването на файла.";
				$_SESSION['notification']['type'] = "error";
				header("Location: process.php");
				exit;
			}
			
			if($_FILES['file']['size'] > MAX_FILE_SIZE) {
				$_SESSION['notification']['message'] = "Надвишен е максимално допустимият размер на файла.";
				$_SESSION['notification']['type'] = "error";
				header("Location: process.php");
				exit;
			}
			
			$csvRaw = csvToArray($_FILES['file']['tmp_name']);
			
			//Not parsing MIME types as there are many that could correspond to *.csv
			if(!is_array($csvRaw) || empty($csvRaw)) {
				$_SESSION['notification']['message'] = "Прикаченият CSV файл е невалиден или празен.";
				$_SESSION['notification']['type'] = "error";
				header("Location: process.php");
				exit;
			}
			
			$students = array();
			
			foreach($csvRaw as $row) {
				$students[$row['fn']] = $row;
				$students[$row['fn']]['post_id'] = null;
			}
			
			//Save students to DB
			if($hasStudents) {
				$studentsDBRaw = $studentModel->getAllStudents();
				$studentsDB = array();
				
				foreach($studentsDBRaw as $student) {
					$studentsDB[$student['fn']] = $student;
				}
			}
			
			foreach($students as $student) {
				if($hasStudents) {
					if(isset($studentsDB[$student['fn']])) {
						$studentModel->updateStudent($student);
					}
					else {
						$studentModel->saveStudent($student);
					}
				}
				else {
					$studentModel->saveStudent($student);
				}
			}
		}
		else if($_POST['scantarget'] == "db") {
			if(!$hasStudents) {
				$_SESSION['notification']['message'] = "Възникна грешка. Моля, опитайте отново!";
				$_SESSION['notification']['type'] = "error";
				header("Location: process.php");
				exit;
			}
			
			$studentsDB = $studentModel->getAllStudents();
			
			$students = array();
			
			foreach($studentsDB as $student) {
				$students[$student['fn']] = $student;
			}
		}
		
		$scanFB = false;
		if(isset($_POST['scanfb'])) {
			$scanFB = true;
		}
		
		$generateInvites = false;
		if(isset($_POST['invgen'])) {
			$generateInvites = true;
		}
		
		$postModel = new Post_model();
		
		$autoGeneratedPostsDB = $postModel->getAutoGeneratedPosts();
		$autoGeneratedPosts = array();
		
		foreach($autoGeneratedPostsDB as $post) {
			$autoGeneratedPosts[$post['id']] = true;
		}
		
		if($scanFB) {
			//Get data from FB group
			$fbData = $facebookModel->getFacebookGroupPosts();
			
			$fbInvitations = array();
			$fbInvitationsGenerated = array();
			$fbInvitationsGeneratedNew = array();
			
			foreach($fbData['data'] as $post) {
				//Post is not an invitation unless it has a picture
				if(isset($post['full_picture'])) {
					//Check if image caption contains a student's faculty number (minimum 5 consecutive digits)
					preg_match_all('/\d{5,}/', $post['message'], $matches);
					
					foreach($matches[0] as $fn) {
						if(isset($students[$fn])) {
							$postArray = array('id' => explode("_", $post['id'])[1],
												'created_time' => date("Y-m-d H:i:s", strtotime($post['created_time'])),
												'message' => $post['message'],
												'picture' => $post['full_picture'],
												'likes' => $post['reactions']['summary']['total_count'],
												'comments' => $post['comments']['summary']['total_count']);
							
							//Check if the post was auto generated previously
							if(isset($autoGeneratedPosts[$postArray['id']])) {
								$postArray['auto_generated'] = 1;
								$fbInvitationsGenerated[$fn] = $postArray;
							}
							else {
								$postArray['auto_generated'] = 0;
								$fbInvitations[$fn] = $postArray;
							}
						}
					}
				}
			}
		
			//Auto generate invitations
			if($generateInvites) {
				foreach($students as $fn => $student) {
					if(!isset($fbInvitations[$fn]) && !isset($fbInvitationsGenerated[$fn])) {
						//If the student hasn't uploaded an invitation until midnight before their presentation time, generate an invite
						$midnight = date("Y-m-d 00:00:00", strtotime($student['presentation_time']));
						
						if(time() >= strtotime($midnight)) {
							//Generate invitation, post it to FB and save post to db
							$post = $facebookModel->generatePost($student);
							
							$postArray = array('id' => explode("_", $post['id'])[1],
												'created_time' => date("Y-m-d H:i:s", strtotime($post['created_time'])),
												'message' => $post['message'],
												'picture' => $post['full_picture'],
												'likes' => 0,
												'comments' => 0,
												'auto_generated' => 1);
												
							$fbInvitationsGeneratedNew[$fn] = $postArray;
						}
					}
				}
			}
		
			//Delete previous auto generated posts if students uploaded their own invitations
			foreach($fbInvitationsGenerated as $fn => $post) {
				if(isset($fbInvitations[$fn])) {
					//Delete post from FB
					//This doesn't work; says there were insufficient permissioons provided even if ALL of them are provided; bug report opened
					$facebookModel->deletePost($post['id']);
					unset($fbInvitationsGenerated[$fn]);
				}
			}
		
			//Save FB posts to database
			//Clear old post information
			$postModel->truncatePosts();
			
			$saveSetting = array('name' => 'fb_last_update',
								 'value' => date("Y-m-d H:i:s"));
								 
			$settingModel->saveSetting($saveSetting);
			
			$saveSources = array($fbInvitations, $fbInvitationsGenerated, $fbInvitationsGeneratedNew);
			
			foreach($saveSources as $save) {
				foreach($save as $fn => $post) {
					$postModel->savePost($post);
								
					$saveStudent = array('fn' => $fn,
										'post_id' => $post['id']);
					
					$studentModel->updateStudentPost($saveStudent);
				}
			}
		}
		
		//Scan directory if it was uploaded and show results
		if(isset($_FILES['directory']) && $_FILES['directory']['error'][0] == 0) {
			$allowedImageExtensions = array('png', 'jpg', 'jpeg');
			$studentInvitations = array();
			
			foreach($students as $fn => $student) {
				$hasInvitation = false;
				
				foreach($allowedImageExtensions as $extension) {
					$invitationName = $fn . "_invite." . $extension;
					
					if(isset($folderInvitations[$invitationName])) {
						$student['filename'] = $invitationName;
						$studentInvitations[] = $student;
						$hasInvitation = true;
						break;
					}
				}
				
				if(!$hasInvitation) {
					$studentInvitations[] = $student;
				}
			}
			
			require_once('views/FolderInvitations.php');
			exit;
		}
		
		$_SESSION['notification']['message'] = "Сканирането беше успешно!";
		$_SESSION['notification']['type'] = "success";
		header("Location: process.php");
		exit;
	}
	
	require_once('views/Process.php');
	require_once('views/footer.php');
?>