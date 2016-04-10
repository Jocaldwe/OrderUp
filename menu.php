<?php

/*

UserCake Version: 2.0.2

http://usercake.com

*/



require_once("models/config.php");

if (!securePage($_SERVER['PHP_SELF'])){die();}

if(!empty($_POST))
{
	$orderitem = new orderItem();
	$orderitem->itemid = $_POST['itemid'];
	$orderitem->price = $_POST['itemprice'];
	$orderitem->calories = $_POST['itemcals'];
	if(isset($_POST['size']))
	{
		$orderitem->size = $_POST['size'];
	}
	else
	{
		$orderitem->size = NULL;
	}
	
	$options = array();
	
	foreach($_POST['items'] as $option)
	{
		echo $option;
		$options[] = $option;
	}
	$orderitem->options = $options;
	
	if(!isset($_SESSION['order']))
	{
		$order = new order();
		if(!isset($loggedInUser->user_id))
		{
			$order->user = 0;
		}
		else
		{
			$order->user = $loggedInUser->user_id;
		}
		$_SESSION['order'] = serialize($order);
	}
	
	$order = unserialize($_SESSION['order']);
	$order->addItem($orderitem);
	$_SESSION['order'] = serialize($order);	
}

require_once("header.php");


?>

<script>

$(document).ready(function(){
	
	$("div.accordion").accordion({
		heightStyle: "content",
		collapsible: true,
		active: false
	});
	
	$("body").on("click", "img[class=image]", function(e){
		
		var itemid = $(this).attr("id");
		
		$.ajax({
			type: "GET",
			url: "itemdata.php",
			data: {itemid: itemid},
			complete: function(result)
			{
				var form = JSON.parse(result.responseText);
				$("#formcontent").append(form);
			},
			error: function(){
				alert("Could not find information for item id #"+ itemid);
			}
			
		});
		
		$("#heading").append("Customize Item");
		$(".popup").fadeIn(300);
		$("#screen").fadeTo(300, 0.7);
		
	});
	
	$(document).on("change", "input[name=size]:radio", function(){
		
		var cals = parseInt($("input[type=checkbox]:checked").attr("data-calories"));
		var newcals = 0;
		
		var price = parseFloat($("#price").attr("data-price"));
		var newprice = 0;
		
		if($(this).val() == 0)
		{
			newcals = cals;
			newprice = price;
		}
		else if($(this).val() == 1)
		{
			newcals = (cals * 1.2).toFixed(0);
			newprice = (price * 1.1).toFixed(2);
		}
		else if($(this).val() == 2)
		{
			newcals = (cals * 1.4).toFixed(0);
			newprice = (price * 1.2).toFixed(2);
		}
		
		$("#calories").empty();
		$("#calories").append(newcals);
		$("#itemcals").val(newcals);
		
		$("#price").empty();
		$("#price").append("$"+newprice);
		$("#itemprice").val(newprice);
	});
	
	$(document).on("change", "input:checkbox", function(){
		var sum = recalculate();
		
		$("#calories").empty();
		$("#calories").append(sum);
		$("#itemcals").val(sum);
	});
	
	$(document).on("submit", "form", function(){
		$("input").removeAttr("disabled");
	});
	
});

function recalculate(){
	
	var sum = 0;
	$("input[type=checkbox]:checked").each(function(){
		sum += parseInt($(this).attr("data-calories"));
	});
	
	return sum;
}

function closePopup()
{
	$(".popup").fadeOut(100);
	$("#screen").fadeOut(100);
	$("#formcontent").empty();
	$("#heading").empty();
}

$(function() {
    $( "#draggable" ).draggable();
  });



</script>


<div id='main'>

	<div class='paform'>
	<h1>Our Menu!</h1>
	<p>*Calorie information is based on typical menu preparation. Adding or removing ingredients may change the calorie content of our items, and changes will be reflected when placing your order.</p>
	<div id="menu" class="accordion">
		
	<?php
	
	$categories = array("Entrées", "Sides", "Drinks", "Desserts");
	
	for($i = 0; $i <= 3; $i++)
	{
		echo "<h3><a class='menu' href='#'>{$categories[$i]}</a></h3>";
		echo "<div>";
		
		$category = $i + 1;
		
		global $pdo;
		$stmt = $pdo->prepare("SELECT * FROM menuitem WHERE category = :category");
		$stmt->execute(array("category" => $category));
		
		while($row = $stmt->fetch(PDO::FETCH_OBJ))
		{
			echo "  <figure>
					<img class='image' src='images/{$row->image}' alt='{$row->name}' id='{$row->id}' />
					<br />
					<figcaption>
					<h6>{$row->name}</h6>
					<h7>$$row->price</h7>
					<h7>". getCalorieCount($row->id) ." calories*</h7>
					</figcaption>
					</figure>";			
		}
		
		echo "</div>";
		
	}
	
	?>
		
	</div>
	</div>


</div>

<div id='draggable' class='popup ui-widget-content'>
	<div class='popuphead'>
	<span class='heading' id="heading"></span>
	<span class='cancel fa fa-close icon-a' onclick='closePopup();'></span>
	<div style='clear: both;'></div>
	</div>
	<div class='popupcontent wrapper' id='formcontent'>
	
	
	</div>
</div>

<div id='screen'>
</div>

<?php require_once("footer.php"); ?>