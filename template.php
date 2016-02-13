<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("header.php");

echo "
<div id='main'>

</div>
";

require_once("footer.php");

?>
