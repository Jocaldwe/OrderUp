<nav class="navbar navbar-inverse navbar-fixed-top sticky">
	<div class="container-fluid" id="navfluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigationbar">
				<span class="sr-only">Toggle navigation</span> 
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>          
			<a class="navbar-brand" href="index.php">
				Order Up!
			</a>        
		</div>        
		<div class="collapse navbar-collapse" id="navigationbar">
		<ul class="nav navbar-nav">		  		  
		<?php		  			
			if(!securePage($_SERVER['PHP_SELF'])){die();}						
			if(isUserLoggedIn())
			{
				if($loggedInUser->checkPermission(array(3)))
				{					
					echo "<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
								<i class='fa fa-2x fa-user-secret'></i> 
								<b class='caret'></b>
							</a>
							<ul class='dropdown-menu'>
								<li><a href='additem.php'>Add Menu Item</a></li>
								<li><a href='addcomponent.php'>Add Component Item</a></li>
								<li><a href='user_settings.php'>User Settings</a></li>
								<li><a href='logout.php'>Logout</a></li>
							</ul>
							</li>";				
				}
				if($loggedInUser->checkPermission(array(2)))
				{
					echo "<li class='dropdown'>
						  <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
							<i class='fa fa-2x fa-cog'></i>
							<b class='caret'></b>
							</a>
							<ul class='dropdown-menu'>
							<li><a href='admin_configuration.php'>Admin Configuration</a></li>
							<li><a href='admin_pages.php'>Admin Pages</a></li>
							<li><a href='admin_permissions.php'>Admin Permissions</a></li>
							<li><a href='admin_users.php'>Admin Users</a></li>
						</ul>
						</li>";
				}
				if($loggedInUser->checkPermission(array(1)))
				{
					echo "<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
								<i class='fa fa-2x fa-user'></i> 
								<b class='caret'></b>
							</a>
							<ul class='dropdown-menu'>
							<li><a href='favorites.php'>Favorites</a></li>
							<li><a href='orders.php'>My Orders</a></li>
							<li><a href='user_settings.php'>User Settings</a></li>
							<li><a href='logout.php'>Logout</a></li>
							</ul>
							</li>";
							echo "<li><a href='order.php'><i class='fa fa-2x fa-shopping-basket'></i></a></li>";
				}							
			}						
			else
			{				
				echo "<li><a href='login.php'><i class='fa fa-2x fa-sign-in'></i></a></li>";				
				echo "<li><a href='order.php'><i class='fa fa-2x fa-shopping-basket'></i></a></li>";
			}
		?>
		</ul>        
		</div><!--/.nav-collapse -->
	</div>
</nav>