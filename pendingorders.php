<?php

	require_once("models/config.php");
	
	$stmt = $pdo->prepare("SELECT * FROM customerorder WHERE status = 0");
	$stmt->execute();
	
	$orders = array();
	
	while($row = $stmt->fetch(PDO::FETCH_OBJ))
	{
		$items = array();
		
		$stmt2 = $pdo->prepare("SELECT orderitem.id AS id, orderitem.menuitemId, name, size FROM orderitem LEFT JOIN menuitem ON menuitemId = menuitem.id WHERE orderId = :id");
		$stmt2->execute(array("id" => $row->id));
		
		while($row2 = $stmt2->fetch(PDO::FETCH_OBJ))
		{
			$itemoptions = array();
			
			$stmt3 = $pdo->prepare("SELECT name FROM orderitemcomponent LEFT JOIN componentitem ON componentitemId = componentitem.id WHERE orderitemId = :id");
			$stmt3->execute(array("id" => $row2->id));
			while($row3 = $stmt3->fetch(PDO::FETCH_OBJ))
			{
				$itemoptions[] = $row3->name;
			}
			$items[] = array("menuitem" => $row2->name, "itemsize" => $row2->size, "components" => $itemoptions);
		}
		$order = array("orderid" => $row->id, "ordertime" => $row->ordertime, "items" => $items);
		$orders[] = $order;		
	}
	
	echo json_encode($orders);

?>