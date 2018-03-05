<html>
<head><title>Message Board</title></head>

<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();


if($_SESSION['user'] == null)
{
header( "refresh:0; url=login.php" );
}

$host = "localhost";
$db = "board";
$dbUser = "root";
$dbPassword = "Addy$$";
$errorMessage = "" ;
try {
    $pdh = new PDO("mysql:host=$host;dbname=$db",$dbUser,$dbPassword);
    $pdh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection Failed";
}

?> 



<body>

<div style="color: white;display:inline-block;margin-top:10px;margin-right:30px;float:right">
<form action=board.php method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
</div>
<?php
    if(isset($_POST['logout'])) {
        session_unset($_SESSION);
        session_destroy();
        echo '<script>window.location="login.php"</script>';
    }
?>

<form action="board.php" method="GET">
			<div>
				<label><b>Type the message in the chat box below</b></label><br/><br/>
				<textarea  name="postData" id="postDataId"></textarea><br/><br/>
				<input type="submit" value = " New Post ">
			</div>
		</form>






<?php 
		
	if(isset($_GET['postData'])) {
		if($_GET['postData'] != null) {
				$id = uniqid();
    				$replyto = null;
    				$postedby = $_SESSION['user'];
				$message = $_GET['postData'];
				$query = "insert into posts(id, replyto, postedby, datetime, message) values(:id,:replyto,:postedby,NOW(),:message)";
				$PreparedQuery = $pdh->prepare($query);
				$PreparedQuery->bindParam(':id', $id);
    				$PreparedQuery->bindParam(':replyto', $replyto);
   				$PreparedQuery->bindParam(':postedby', $postedby);
    				$PreparedQuery->bindParam(':message', $message);
				$PreparedQuery -> execute();
					
				}
			}

	if(isset($_GET['replyclicked']) && isset($_GET['DataToReply'])) {
				
				if($_GET['DataToReply'] != null) {
					
					$id = uniqid();
					$replyto = $_GET['postId'];
					$postedby = $_SESSION['user'];
					$message = $_GET['DataToReply'];
					
					
					
					$query = "insert into posts(id, replyto, postedby, datetime, message) values (:id, :replyto, :postedby,NOW(),:message)";
					$PreparedQuery = $pdh->prepare($query);
					$PreparedQuery->bindParam(':id', $id);
    					$PreparedQuery->bindParam(':replyto', $replyto);
   					$PreparedQuery->bindParam(':postedby', $postedby);
    					$PreparedQuery->bindParam(':message', $message);
					$PreparedQuery -> execute();
					
					
				}
			}
			
			
			
			
?>

<br/>
<hr>
<hr>

<?php
	$query = 'SELECT id, postedby, fullname, datetime, replyto, message FROM POSTS, USERS WHERE POSTEDBY = USERNAME order by datetime desc';
	$QueryStatement = $pdh->prepare($query);
	$QueryStatement -> execute();
	$result = $QueryStatement->fetch();
	if($result)
		{	echo  '<fieldset>';
			echo '<legend>Chats</legend>';
			echo '<div>';
				while($result) {
					
						
					echo '<form name = "newform'.$result['id'].'" action = "board.php" method = "GET">';
					if($result['replyto']){
					echo '<div align = "right">';
					echo '<label>Message Id : '.$result['id'].'</label><br/>';
					echo '<label>Username : '.$result['postedby'].'</label><br/>';
					echo '<label>Full Name : '.$result['fullname'].'</label><br/>';
					echo '<label>This Message Is a Reply To : '.$result['replyto'].'</label><br/>';
					echo '<label>Message :- '.$result['message'].'</label><br/>';
					echo '<label>Date and Time : '.$result['datetime'].'</label><br/>';
					echo '<input type = "hidden" name = "postId" value = "'.$result['id'].'">';
					echo '<input type = "hidden" id = "newpost'.$result['id'].'" name = "DataToReply">';?>
					<div align = "right"> 
					<input type = "submit" name = "replyclicked" value = "Reply" onclick ="getInfo('<?php echo $result['id'] ?>')">
					</div>
					<?php
					echo '</div>';
					}
					else{
					echo '<div>';
					echo '<label>Message Id : '.$result['id'].'</label><br/>';
					echo '<label>Username : '.$result['postedby'].'</label><br/>';
					echo '<label>Full Name : '.$result['fullname'].'</label><br/>';
					echo '<label>Message :- '.$result['message'].'</label><br/>';
					echo '<label>Date and Time : '.$result['datetime'].'</label><br/>';
					echo '<input type = "hidden" name = "postId" value = "'.$result['id'].'">';
					echo '<input type = "hidden" id = "newpost'.$result['id'].'" name = "DataToReply">'; ?>
					<div align = "left"> 
					<input type = "submit" name = "replyclicked" value = "Reply" onclick ="getInfo('<?php echo $result['id'] ?>')">
					</div>
					<?php
					}
					?>
					<br/>
					
					
					
					
					<br/>
					<?php
					echo '<br/>';
					echo '</div>';
					
					echo '</form>';
					$result = $QueryStatement->fetch();
				}
				echo '</div>';
				echo '<fieldset>';
				
			}
			else {
				echo '<br/><br/>';
				echo '<label>No posts available</label>';
			}
			
		
		?>

<script type="text/javascript">
		
			function getInfo(postId) {
				var message  = document.getElementById("postDataId").value;
				document.getElementById("newpost"+postId).value = message;
			}
			
		</script>

<form action="board.php" method="GET">
		
	<div>
			<input type="submit" name = "clearData" value = "Clear Posts">
	</div>
</form>

<?php
if(isset($_GET['clearData']))
	{
		$query = 'DELETE FROM POSTS';
		$QueryStatement = $pdh->prepare($query);
		$QueryStatement -> execute();
	}




?>

</body>
</html>




