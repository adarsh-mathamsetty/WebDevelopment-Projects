<html>
<head>
<title>DropBox Uploader</title>
</head>
<body>
<div style="height: 50px; text-align: center; background-color: black;">
<font size="7" style="color: white"><b>DropBox</b></font>
</div>

<hr>

<?php
    
    // display all errors on the browser
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    /**
     * DropPHP Demo
     *
     * http://fabi.me/en/php-projects/dropphp-dropbox-api-client/
     *
     * @author     Fabian Schlieper <fabian@fabi.me>
     * @copyright  Fabian Schlieper 2012
     * @version    1.1
     * @license    See license.txt
     *
     */
    
    
    require_once 'demo-lib.php';
    demo_init(); // this just enables nicer output
    
    // if there are many files in your Dropbox it can take some time, so disable the max. execution time
    set_time_limit( 0 );
    
    require_once 'DropboxClient.php';
    
    /** you have to create an app at @see https://www.dropbox.com/developers/apps and enter details below: */
    /** @noinspection SpellCheckingInspection */
    $dropbox = new DropboxClient( array(
                                        'app_key' => "uuo8kfo2aksoj49",      // Put your Dropbox API key here
                                        'app_secret' => "d5ia4itwa3qz5xe",   // Put your Dropbox API secret here
                                        'app_full_access' => false,
                                        ) );
    
    
    /**
     * Dropbox will redirect the user here
     * @var string $return_url
     */
    $return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";
    
    // first, try to load existing access token
    $bearer_token = demo_token_load( "bearer" );
    
    if ( $bearer_token ) {
        $dropbox->SetBearerToken( $bearer_token );
        //echo "loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "\n";
    } elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
    {
        // get & store bearer token
        $bearer_token = $dropbox->GetBearerToken( null, $return_url );
        demo_store_token( $bearer_token, "bearer" );
    } elseif ( ! $dropbox->IsAuthorized() ) {
        // redirect user to Dropbox auth page
        $auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
        die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
    }
    
    
    
    
    
    ?>
<?php $accountName = $dropbox->GetAccountInfo()->display_name; ?>

<p style="text-align: center;"><font size="5"><b> Account Holder:</b><?= $accountName ?></font></p>


<div>
<fieldset>
<legend>You can Upload files into DropBox here:</legend>
<form action="album.php" method="POST" enctype="multipart/form-data">
Choose a File : <input type="file" name="upload"><br/>
<input type="submit" value="Upload File">
</fieldset>
</form>
</div>

<?php
    
    
    
    if(isset($_FILES['upload'])) {
        if($_FILES['upload']['tmp_name'] != ""){
            $fpath = $_FILES['upload']['tmp_name'];
            $fileName = $_FILES['upload']['name'];
            $dropbox->UploadFile($fpath,$fileName);
        }
        else {
            echo "<b>ERROR:Please Select A File</b>";
        }
        
    }
    
    if(isset($_GET['view']))
    {
        $test_file = $_GET['view'];
        $working_directory = getcwd();
        $dropbox->DownloadFile( '/'.$_GET['view'], $working_directory.'/'.$test_file );
    }
    
    if(isset($_GET['imgPath']) && isset($_GET['operation']) && $_GET['operation'] == "delete") {
        $fpath = $_GET['imgPath'];
        $ArrayofFiles = $dropbox->GetFiles("",false);
        foreach ($ArrayofFiles as $key=>$value) {
            if((string)$fpath == (string)$value->path) {
                $dropbox->Delete($value->path);
            }
        }
    }
    ?>
<div>
<form action="album.php" method="GET">
<p ><font size="6"><b>Images:</b></font></p>

<?php
    $files= $dropbox->GetFiles("",false);
    foreach ($files as $value) {
        ?>

<a href="album.php?view=<?= basename($value->path) ?>"><?= basename($value->path)?></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" value="Delete" onclick="deleteFile('<?php echo $value->path; ?>')">

<?php
    }
    ?>

<input type="hidden" name="imgPath" id="imgPathId">
<input type="hidden" name="operation" id="operationId">
</form>
</div>


<?php
    if(isset($_GET['view']))
    {
        ?>
<p> You are viewing <?= $_GET['view'] ?> and got DOWNLOADED in project8 folder. </p>
<img src='<?= $dropbox->GetLink($_GET['view'],false) ?>' width='350'/>
<?php
    }
    ?>

<script type="text/javascript">

function deleteFile(FilePath) {
				document.getElementById("imgPathId").value = FilePath;
				document.getElementById("operationId").value = "delete";
}

</script>


</body>
</html>
