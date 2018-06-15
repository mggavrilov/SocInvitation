<?php
	require_once("helpers/CSVHelper.php");
	
	define("MAX_FILE_SIZE", 20000000); //20MB
	
	if(!isset($_FILES['file'])) {
		header("Location: index.php");
		exit;
	}
	
	if($_FILES['file']['error'] != 0) {
		//file upload error
		header("Location: index.php");
		exit;
	}
	
	if($_FILES['file']['size'] > MAX_FILE_SIZE) {
		//exceeded allowed file size
		header("Location: index.php");
		exit;
	}
	
	$csvRaw = csvToArray($_FILES['file']['tmp_name']);
	
	//Not parsing MIME types as there are many that could correspond to *.csv
	if(!is_array($csvRaw) || empty($csvRaw)) {
		//Invalid CSV
		header("Location: index.php");
		exit;
	}
	
	$csvArray = array();
	
	foreach($csvRaw as $row) {
		$csvArray[$row['id']] = $row;
	}
	
	$invitations = array();
	
	//get data from FB
	foreach($fbData['data'] as $post) {
		//If post has picture (i.e. post is an invitation)
		if(isset($post['full_picture'])) {
			//Check if image caption contains a student's faculty number
			preg_match_all('/\d{5,}/', $post['message'], $matches);
			
			foreach($matches[0] as $match) {
				if(isset($csvArray[$match])) {
					$invitations[$match] = array('post_id' => $post['id'],
												'message' => $post['message'],
												'picture' => $post['full_picture'],
												'created_time' => $post['created_time'],
												'likes' => $post['likes']['summary']['total_count'],
												'comments' => $post['comments']['summary']['total_count']);
				}
			}
		}
	}
	
	$folderInvitations = array();
	
	//get data from selected folder
	foreach($_FILES['directory']['name'] as $invitationName) {
		$folderInvitations[$invitationName] = true;
	}
	
	
?>