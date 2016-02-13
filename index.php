<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("header.php");
?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel"><!-- Indicators -->      
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
		</ol>     
	<div class="carousel-inner" role="listbox">
		<?php
			global $pdo;
			$stmt = $pdo->query("SELECT * FROM menuitem WHERE featured = 1");
			$count = 0;		
			while($row = $stmt->fetch(PDO::FETCH_OBJ))
			{
				if($count == 0)	
				{
					echo "<div class='item active'>";
				}
				else
				{
					echo "<div class='item'>";
				}
				echo "<img src='images/{$row->image}' alt='{$row->name}' />
					  <div class='container'>
					  <div class='carousel-caption'>
					  <p>{$row->name}* for only $$row->price!</p>
					  <p>*Contains "; 	
					  echo getCalorieCount($row->id);
					  echo " calories.</p></div>					</div>				</div>";
					  $count++;		
			}
		?>
		</div>      
		<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
		</a>
		</div><!-- /.carousel -->
<?
require_once("footer.php");

?>
