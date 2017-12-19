<?php

$token='[YOUR_TOKEN]';
$from='[PROJECT_ID]';
$to='[PROJECT_ID]';

function getInboxTasks() {
	global $token, $from;
	$url = 'https://beta.todoist.com/API/v8/tasks?token='.$token.'&project_id='.$from;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result,true);
}

function formListTask($tasks){
    global $from;
    $listTask = array();
	$listTask[$from]= array();
	foreach ($tasks as $task ){
		array_push($listTask[$from], $task['id']);
	}
	return json_encode($listTask) ;
}

function moveTasks($listTask){
	global $token, $from, $to;
	$url = "https://todoist.com/api/v7/sync";
	$post_data = [
		'token' => $token,
		'commands' => 
			'[{"type": "item_move", ' .
			'"uuid": "818f108a-36d3-423d-857f-62837c245f3b", ' . 
			'"args": {"project_items": '.$listTask.', "to_project":'.$to.'}}]'
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output = curl_exec($ch);
	curl_close($ch);
	echo $output;
}

moveTasks(formListTask(getInboxTasks()));




?>