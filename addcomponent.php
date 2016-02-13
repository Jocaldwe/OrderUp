<?php



require_once("models/config.php");

if (!securePage($_SERVER['PHP_SELF'])){die();}

require_once("header.php");

if(!empty($_POST))
{
	global $pdo;
	$stmt = $pdo->prepare("INSERT INTO componentitem (name, caloriecount) VALUES (:name, :caloriecount)");
	$stmt->execute(array("name" => $_POST['compname'], "caloriecount" => $_POST['calories']));
}

?>
<div id="main">
	<div class='paform'>
		<h1>Add Component Item</h1>
		
		<form action="addcomponent.php" method="post" enctype="multipart/form-data">
		
			<label for="compname">Component Name</label>
			<input type="text" name="compname" />
			
			<label for="calories">Calorie Count</label>
			<input type="number" min="0" step="1" name="calories">
			
			<label>&nbsp;</label>

			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
</div>

<?php require_once("footer.php"); ?>