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
        <title>Second authentication</title>
    </head>
    <header>
        <h1>Hi <?php echo $_SESSION['user_name'];?>. Welcome to Second authentication.</h1>
    </header>
    <body>
        
        
        <main>
            <?php
                if(!isset($_SESSION['pass'])){
                    $curl = curl_init();
                    $_SESSION['pass']=strval(rand(1000,9999));
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://localhost/esb-rest-api/put-pass',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('pub_name' => $_SESSION['user_name'],'pass' => $_SESSION['pass']),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://fatsms.com/send-sms',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('to_phone' => $_SESSION['phone'],'message' => $_SESSION['pass'],'api_key' => 'af55f01c-da8e-47d7-b62f-d2ad4c6b5473'),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $_SESSION['count'] = 3;
                }
                            
                if (isset($_POST['pass'])){
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://localhost/esb-rest-api/get-pass',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('pub_name' => $_SESSION['user_name']),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    if(strval('"'.$_POST['pass'].'"')==$response){
                        header("Location: home.php");
                    }
                    else{  
                        --$_SESSION['count']; 
                        if($_SESSION['count']==0)
                        {
                            header("Location: logout.php");
                        }
                        else{
                            echo ('<p>you have only '. $_SESSION['count'].' more try left.<p><br>');
                        } 
                    }
                }
            ?>
            <p>Please enter the 4 digit code sent to your mobile.<p>
            <div class ="form">
                <form action="" method="post" name="second" >
                    <input type="text" id='pass' name="pass" placeholder="4 digit code" required tabindex="1"><br>
                    <input name="submit" type="submit" value="Submit" tabindex="2">
                </form>
                
            </div>
            
          
            <div>
            <p>To Logout<a href='logout.php'>Log Out</a></p>
            </div>
        </main>
        <footer>2021 Abul Kasem Mohammed Omar Sharif</footer>
        
    </body>
</html>