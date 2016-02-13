<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

class loggedInUser {
	public $email = NULL;
	public $hash_pw = NULL;
	public $user_id = NULL;
	
	//Simple function to update the last sign in of a user
	public function updateLastSignIn()
	{
		global $pdo;
		$time = time();
		$stmt = $pdo->prepare("UPDATE users
			SET
			last_sign_in_stamp = :time
			WHERE
			id = :user");
		$stmt->execute(array("time" => $time, "user" => $this->user_id));
	}
	
	//Return the timestamp when the user registered
	public function signupTimeStamp()
	{
		global $pdo;
		
		$stmt = $pdo->prepare("SELECT sign_up_stamp as timestamp
			FROM users
			WHERE id = :id");
		$stmt->execute(array("id" => $this->user_id));
		$obj = $stmt->fetch(PDO::FETCH_OBJ);
		return ($obj->timestamp);
	}
	
	//Update a users password
	public function updatePassword($pass)
	{
		global $pdo;
		$secure_pass = generateHash($pass);
		$this->hash_pw = $secure_pass;
		$stmt = $pdo->prepare("UPDATE users
			SET
			password = :pass 
			WHERE
			id = :id");
		$stmt->execute(array("pass" => $secure_pass, "id" => $this->user_id));	
	}
	
	//Update a users email
	public function updateEmail($email)
	{
		global $pdo;
		$this->email = $email;
		$stmt = $pdo->prepare("UPDATE users
			SET 
			email = :email
			WHERE
			id = :id");
		$stmt->execute(array("email" => $email, "id" => $this->user_id));
	}
	
	//Is a user has a permission
	public function checkPermission($permission)
	{
		global $pdo,$master_account;
		
		//Grant access if master user
		
		$stmt = $pdo->prepare("SELECT id 
			FROM user_permission_matches
			WHERE user_id = :id
			AND permission_id = :check
			LIMIT 1
			");
		$access = 0;
		foreach($permission as $check){
			if ($access == 0){
				$stmt->execute(array("id" => $this->user_id, "check" => $check));
				if ($stmt->rowCount() > 0){
					$access = 1;
				}
			}
		}
		if ($access == 1)
		{
			return true;
		}
		if ($this->user_id == $master_account){
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	//Logout
	public function userLogOut()
	{
		destroySession("userCakeUser");
	}	
}

?>