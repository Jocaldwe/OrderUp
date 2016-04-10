<?php



require_once("models/config.php");

if (!securePage($_SERVER['PHP_SELF'])){die();}

require_once("header.php");

if(!empty($_POST))
{
	if(isset($_POST['medium']))
	{
		$medium = TRUE;
	}
	else
	{
		$medium = FALSE;
	}
	if(isset($_POST['large']))
	{
		$large = TRUE;
	}
	else
	{
		$large = FALSE;
	}
	if(isset($_POST['featured']))
	{
		$featured = TRUE;
	}
	else
	{
		$featured = FALSE;
	}
	
	$filename = $_FILES['file']['name'];
	$filename = preg_replace('/[^a-zA-Z0-9-_\.]/','', $filename);
	
	$finfo = finfo_open(FILEINFO_MIME);
		
	$allowedExts = array("image/png","image/gif","image/jpg","image/jpeg","image/JPG","image/JPEG","application/pdf");
	$fileinfo = finfo_file($finfo, $_FILES['file']['tmp_name']);
	
	$infoarray = explode(";", $fileinfo);
	$filetype = $infoarray[0];
	
	if(in_array($filetype, $allowedExts))
	{
		global $pdo;
		$stmt = $pdo->prepare("INSERT INTO menuitem (name, price, medium, large, featured, category, image) VALUES (:name, :price, :medium, :large, :featured, :category, :image)");
		$stmt->execute(array("name" => $_POST['itemname'], "price" => $_POST['price'], "medium" => $medium, "large" => $large, "featured" => $featured, "category" => $_POST['category'], "image" => $filename));
			
		move_uploaded_file($_FILES['file']['tmp_name'], "images/" . $filename);
			
		$itemId = $pdo->lastInsertId();
			
		foreach($_POST['compitem'] as $count => $item)
		{
			$stmt = $pdo->prepare("INSERT INTO menuitemcomponent (itemid, componentid, type) VALUES (:itemid, :componentid, :option)");
			$stmt->execute(array("itemid" => $itemId, "componentid" => $item, "option" => $_POST['option'][$count]));
		}
	}		
}

?>

<script>

var rowNum = 0;
function addRow(frm)
{
	rowNum++;
	
	var list = $("#compitem").clone();
	list.attr("name", "compitem[]");
	list.attr("id", "compitem");
	
	var options = $("#itemoption").clone();
	options.attr("name", "option[]");
	options.attr("id", "itemoption");
	
	var row = "<tr class='items' id='rowNum"+rowNum+"'><td id='container"+rowNum+"'></td><td id='option"+rowNum+"'></td><td><input type='button' value='Remove' onclick='removeRow("+rowNum+");'></td></tr>";
	$(row).insertAfter($("table tr.items:last"));
	$("#container"+rowNum).append(list);
	$("#option"+rowNum).append(options);
}

function removeRow(rnum)
{
	$("#rowNum"+rnum).remove();
}

</script>

	<div class='paform'>
		<h1>Add Menu Item</h1>
		
		<form id="addmenuitem" action="additem.php" method="post" enctype="multipart/form-data">
		
			<label for="itemname">Menu Item Name</label>
			<input type="text" name="itemname" />
			
			<label for="price">Price</label>
			<input type="number" min="0" step="any" name="price">
			
			<label for="small">Small</label>
			<input type="checkbox" checked="checked" name="small" disabled />
			
			<label for="medium">Medium</label>
			<input type="checkbox" name="medium" value="1" />
			
			<label for="large">Large</label>
			<input type="checkbox" name="large" value="1" />
			
			<label for="featured">Featured item?</label>
			<input type="checkbox" name="featured" value="1" />
			
			<label for="category">Category</label>
			<select name="category">
				<option value="1">Entree</option>
				<option value="2">Side</option>
				<option value="3">Beverage</option>
				<option value="4">Dessert</option>
			</select>
			
			<label for="file">Item Image</label>
			<input type="file" name="file" id="file" accept="image/*" required />
			
			<hr />
			<h3>Component Items</h3>
			<table id="itemRows">
			<tr><th>Item Description</th><th>Option Type</th><th></th></tr>
			
			<tr class="items">
				<td>
					<select name="compitem[]" id="compitem">
					<?php
					
						global $pdo;
						$stmt = $pdo->query("SELECT * FROM componentitem WHERE 1 ORDER BY id ASC");
						while($row = $stmt->fetch(PDO::FETCH_OBJ))
						{
							echo "<option value='{$row->id}'>{$row->name}</option>";
						}
					
					?>
					</select>
				</td>
				<td>
					<select name="option[]" id="itemoption">
						<option value="0">Required</option>
						<option value="1">Suggested</option>
						<option value="2">Optional</option>
					</select>*
				</td>
				<td><input onclick="addRow(this.form);" type="button" value="Add Component Item" /></td>
			
			</tr>
			</table>
			
			<label>&nbsp;</label>

			<input type="submit" name="submit" value="Submit" />
			<hr />
			*<em>Required</em> components will be selected by default and cannot be removed by the user.<br />
			 <em>Suggested</em> components will be selected by default and can be removed by the user.<br />
			 <em>Optional</em> components will not be selected by default, but can be added or removed by the user.
		</form>
	</div>

<?php require_once("footer.php"); ?>