<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

if(!empty($_POST))
{
	$order = unserialize($_SESSION['order']);
	
	if($_POST['submit'] == "Place Order")
	{
		$order->calcTotal();
		$order->placeOrder();
	}
	$order->closeOrder();
}

require_once("header.php");

echo "<div id='main'>";

if(!isset($_SESSION['order']))
{
	echo "You have not added any items to your order. View our <a href='menu.php'>menu</a> and add items to your order.";
}
else
{
	if(!isUserLoggedIn())
	{
		echo "You are not logged in. If you would like to log in before submitting your order, click <a href='login.php?redirect=order'>here</a>.";
	}
	
	echo "<form action='order.php' method='post'>";

	$order = unserialize($_SESSION['order']);
	

	foreach($order->items as $item)
	{
		$iteminfo = getItemInfo($item->itemid);
		
		echo "<div class='orderitem'>";
		echo "<div class='itemimage'><img class='image' src='images/{$iteminfo['image']}' /></div>";
		echo "<div class='iteminfo'>";
		echo "<strong>Price:</strong> " . $item->price ."<br />";
		echo "<strong>Calories:</strong> ". $item->calories ."<br />";
		if(!is_null($item->size))
		{
			echo "<strong>Size: </strong>". getSize($item->size) ."<br />";
		}
		echo "<strong>Options: </strong>";
		foreach($item->options as $option)
		{
			echo getComponentName($option). " ";
		}
		echo "</div></div>";
	}
	
	echo "<div class='orderitem'>";
	echo "<strong>Calorie count: </strong>". $order->calories() ."<br /><br />";
	$order->calcTotal();
	echo "<strong>Subtotal: </strong>$". $order->subtotal ."<br />";
	echo "<strong>Tax: </strong>$". $order->tax ."<br />";
	echo "<strong>Total: </strong>$". $order->total ."<br />";
	echo "</div>";

	echo "<input type='submit' name='submit' value='Place Order'>";
	echo "<input type='submit' name='submit' value='Cancel Order'></form>";
	
}



echo "</div>";

require_once("footer.php");

?>
