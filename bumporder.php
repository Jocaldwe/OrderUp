<?php

	require_once("models/config.php");
	
	if(isset($_GET))
	{
		$stmt = $pdo->prepare("UPDATE customerorder SET status = 1 WHERE id = :id");
		$stmt->execute(array("id" => $_GET['orderid']));
	}

?>