<html>
    <head>
        <title>CHAT BOX LOGIN</title>
       
    </head>

    <?php
        session_start();
        error_reporting(0);
        ini_set('display errors', 'On');
	 ?>

<body>

    
       

	<div>
<form action= "login.php" method="POST">
<h1>Welcome To My Chat Application</h1><br/>
<label>Username: </label><input type="text" placeholder="Enter Username" name="user"><br/>
<label>Password:</label><input type="password" placeholder= "Enter Password" name="password"><br/>
<p><button type="submit" name="login">Login</button></p>
</form>
</div>










    <?php
     if(isset($_POST['login'])) {
         $host = "localhost";
         $db = "board";
         $dbUser = "root";
         $dbPassword = "Addy$$";
         $errorMessage = "" ;
         try {
             $username = $_POST['user'];
             $password = md5($_POST['password']);
             $pdo = new PDO("mysql:host=$host;dbname=$db",$dbUser,$dbPassword);
             $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
             $query = 'select * from users WHERE username ="' . $username.'"';
             $QueryDatabase = $pdo->prepare($query);
             $QueryDatabase->execute();
             $numberofRows = $QueryDatabase->rowCount();
             if($numberofRows == 0) {
                 $errorMessage =  "<p>Incorrect Credentials</p>";
             } else {
                 $result = $QueryDatabase->fetch();
                 if($result['username'] == $username && $result['password'] == $password)		
				{ 	
		     
					$_SESSION['user'] = $username;
                     			$_SESSION['fullName'] = $result['fullname'];
					echo "<table>";
		 			echo "<tr><td>" . $result['username'] . "</td><td>" . $_SESSION['user'] . "</td></tr>";
					echo "</table>";
					if($_SESSION['user'] = $username)
						{echo '<script>window.location="board.php"</script>';
						}

				}
			
                     
                  
		else {
		     			$errorMessage =  "<p>Incorrect Credentials</p>";
                     
                 }
             }
         } catch (PDOException $e) {
             $errorMessage =  "<p>Connection Failed</p>";
         }
     }
    ?>

<div >
        <?= $errorMessage ?>
    </div>

 </body>
    </html>