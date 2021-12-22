<?php
include("auth.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css" />
        
        
        <title>Home</title>
    </head>
    <header>
        <h1>Hi <?php echo $_SESSION['user_name'];?>. You can Send your Message.</h1>
    </header>
    <body>
        <main>
        <?php
            if(isset($_POST['submit'])){
                if($_POST['formats']=='json'){
                    $jsonData = '{"pub_name":"'.$_SESSION["user_name"].'","top_name":"'.$_POST["top_name"].'","msg":"'.$_POST["msg"].'"}';
                    $js='{"jsonData":'.$jsonData.',"format":"'.$_POST['formats'].'"}';
                    //echo($js);
                }
                elseif($_POST['formats']=='xml'){
                    $xmlData = '<xml>'.
                        '    <pub_name>'.$_SESSION["user_name"].'</pub_name>'.
                        '    <top_name>'.$_POST["top_name"].'</top_name>'.
                        '    <msg>'.$_POST["msg"].'</msg>'.
                        '</xml>';
                    $js='{"xmlData":"'.$xmlData.'","format":"'.$_POST['formats'].'"}';
                    //echo($js);
                }
                elseif($_POST['formats']=='csv'){
                    $csvData = 'pub_name,top_name,msg\n'. 
                    $_SESSION["user_name"].','.$_POST["top_name"].','.$_POST["msg"];
                    $js='{"csvData":"'.$csvData.'","format":"'.$_POST['formats'].'"}';
                    //echo($js);
                }
                elseif($_POST['formats']=='tsv'){
                    $tsvData = 'pub_name\ttop_name\tmsg\n' . 
                    $_SESSION["user_name"].'\t'.$_POST["top_name"].'\t'.$_POST["msg"];
                    $js='{"tsvData":"'.$tsvData.'","format":"'.$_POST['formats'].'"}';
                    //echo($js);
                }
                try{
                                
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://localhost:3000/publisher',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$js,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                        ));
                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;
                }
                catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
                unset($_POST['submit']);
            }

        ?>
            <div class ="form">
                <form action="" method="POST">
                    <div>
                        <label for="txttopName">Topic Name</label>
                        <input type="text" id='top_name' name="top_name" required >
                    </div>                                
                    <div>
                        <label for="txtmsg">Message</label><br>
                        <textarea name="msg" id='msg' required></textarea>
                    </div>
                    <label for="type">Choose your data type:</label> 
                    <select id="formats" name="formats" required >
                        <option value="json">JSON</option>
                        <option value="xml">XML</option>
                        <option value="csv">CSV</option>
                        <option value="tsv">TSV</option>
                    </select>                                       
                    <input type="submit" name="submit" value="Send">
                </form>
            </div>
            <div>
                <p>To Logout<a href='logout.php'>Log Out</a></p>
            </div>
        </main>
        <footer>2021 Abul, Abdul & Wajid</footer>
    </body>
</html>
