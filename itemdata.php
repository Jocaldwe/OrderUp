<?php

require_once("models/config.php");

global $pdo;

if(isset($_GET['itemid']))
{
	$stmt = $pdo->prepare("SELECT * FROM menuitem WHERE id = :itemid");
	$stmt->execute(array("itemid" => $_GET['itemid']));
	
	$obj = $stmt->fetch(PDO::FETCH_OBJ);
	
	$item = "<form action='menu.php' method='post'><table><tr><td colspan='2'><h2>{$obj->name}</h2></td></tr>";
	$item .= "<input type='hidden' name='itemid' id='itemid' value='{$_GET['itemid']}' />";
	$item .= "<input type='hidden' name='itemprice' id='itemprice' value='{$obj->price}' />";
	$item .= "<input type='hidden' name='itemcals' id='itemcals' value='". getCalorieCount($_GET['itemid']) ."' />";
	$item .= "<tr>";
	$item .= "<td rowspan='2'><img src='images/{$obj->image}' alt='{$obj->name}' class='img-responsive' /></td>";
	$item .= "<td><strong><div id='price' data-price='$obj->price'>$$obj->price</div></strong><br />";
	$item .= "<strong><div id='calories'>". getCalorieCount($_GET['itemid']) ."</div></strong> calories<br />";
	
	if($obj->medium)
	{
		$item .= "<input type='radio' name='size' value='0' checked='checked' />Small<br />
				  <input type='radio' name='size' value='1' />Medium<br />
				  <input type='radio' name='size' value='2' />Large";
	}
	
	$item .= "</td></tr>";
	$item .= "<tr><td>";
	
	$stmt = $pdo->prepare("SELECT componentitem.id as id, name, caloriecount, type FROM menuitemcomponent LEFT JOIN componentitem ON menuitemcomponent.componentid = componentitem.id WHERE itemid = :itemid ORDER BY type asc");
	$stmt->execute(array("itemid" => $_GET['itemid']));
	
	while($row = $stmt->fetch(PDO::FETCH_OBJ))
	{
		if($row->type == 0)
		{
			$checked = "checked='checked'";
			$disabled = "disabled";
		}
		elseif($row->type == 1)
		{
			$checked = "checked='checked'";
			$disabled = "";
		}
		elseif($row->type == 2)
		{
			$checked = "";
			$disabled = "";
		}
		$item .= "<input type='checkbox' name='items[]' value='{$row->id}' data-calories='{$row->caloriecount}' $checked $disabled />{$row->name}<br />";
	}
	
	$item .= "</td></tr>";
	$item .= "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='Add Item to Order' /></td></tr>";
	$item .= "</table></form>";
	
	echo json_encode($item);
}

?>