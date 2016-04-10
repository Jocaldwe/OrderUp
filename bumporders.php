<?php

	require_once("models/config.php");
	require_once("header.php");
	
	?>
	
	<script>
	
	$(document).ready(function(){
		
		function displayOrders()
		{
			$("#orders").empty();
			$.ajax({
				type: "GET",
				url: "pendingorders.php",
				complete: function(result)
				{
					var orders = JSON.parse(result.responseText);
					for(i=0; i<orders.length; i++)
					{
						$("#orders").append("<div class='order' id='"+orders[i].orderid+"'></div>");
						$("#"+orders[i].orderid).append("<strong>Order Id:</strong> "+orders[i].orderid+"<br />");
						$("#"+orders[i].orderid).append("<strong>Order Time:</strong> "+orders[i].ordertime+"<br />");
						
						for(j=0; j<orders[i].items.length; j++)
						{
							$("#"+orders[i].orderid).append("<hr /><strong>Item:</strong> "+orders[i].items[j].menuitem+"<br />");
							var size = orders[i].items[j].itemsize;
							
							if(size != null)
							{
								var displaysize;
								if(size == "0")
								{
									displaysize = "Small";
								}
								else if(size == "1")
								{
									displaysize = "Medium";
								}
								else if(size == "2")
								{
									displaysize = "Large";
								}
								$("#"+orders[i].orderid).append("<strong>Size:</strong> "+displaysize+"<br />");
							}
							$("#"+orders[i].orderid).append("<strong>Components:</strong> (");
							for(k=0; k<orders[i].items[j].components.length; k++)
							{
								$("#"+orders[i].orderid).append(orders[i].items[j].components[k]+", ");
							}
							$("#"+orders[i].orderid).append(")");
						}
						$("#"+orders[i].orderid).append("<br /><button class='bump' data-id='"+orders[i].orderid+"'>Bump Order</button>");
					}
				},
				error: function(){
					alert("Error displaying orders.");
				}
			});
		}
		
		displayOrders();
		
		$(document).on("click", ".bump", function(e){
			
			var order = $(this).attr("data-id");
			
			$.ajax({
				type: "GET",
				url: "bumporder.php",
				data: {orderid: order},
				complete: function(result)
				{
					
				},
				error: function(){
					alert("Couldn't bump order.");
				}
			});
			
			$("#orders").empty();
			displayOrders();
		});
		
		setInterval(displayOrders, 5000);
		
	});
	
	</script>
	
	<?php
	
	echo "<div id='orders' class='orders'>";
	echo "</div>";

?>