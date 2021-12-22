<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css" />
    <title>login</title>
  </head>
  <body>
      <header>
          <h1>Welcome To Publisher Login</h1>
      </header>	
      <?php
          if (isset($_POST['name'])){
              $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://localhost/esb-rest-api/get-phone',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('pub_name' =>$_POST['name']),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response);
            if($response == $_POST['phone'])
            {   
              $_SESSION['user_name']=$_POST['name'];
              $_SESSION['phone']=$_POST['phone'];
              header("Location: text.php");
            }
            else{
              echo "<div>
                      <p>User Name/Password is incorrect.</p>
                      <p><a href='index.php'>Try Again</a></p>
                      <p><a href='registration.php'>For Register </a></p>                   
                      ";
            }
          }
          else{
          ?>
        <main>
          <div class ="form">
              <form action="" method="post" name="login" >
                  
                  <input id="pb_nm" name="name"  placeholder="User Name" type="text"  required tabindex="1"><br>
                  <input type="password" id='phone' name="phone" placeholder="Password" required tabindex="2"><br>
                  <input name="submit" type="submit" value="Login" tabindex="3">
              </form>
              
          </div>
          
          <div id="login">
              
              
          </div>
          <div>
          <p>Not registered yet? <a href='registration.php'>Register Here</a></p>
          </div>
        </main>

          <?php

          }  
      ?>
      
      <footer>2021 Abul, Abdul & Wajid</footer>
  </body>
</html>


