<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css" />
    <title>Register</title>
  </head>
  <body>
      <header>
          <h1>Welcome To Publisher Registration</h1>
      </header>	
      <?php
          if (isset($_POST['name']) && isset($_POST['phone'])){
              $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://localhost/esb-rest-api/put-pub',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('pub_name' =>$_POST['name'],'phone' => $_POST['phone'])
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response);
            if($response == 1)
            {   echo("<p>Registration Successful</p>
                <p><a href='index.php'>Go to Login</a></p>");
            }
            else{
              echo "<div>
                      <p>Try with another User Name.</p>
                      <p><a href='registration.php'>Try Again</a></p>
                      <p><a href='index.php'>Go to Login</a></p>                   
                    </div>";
            }
          }
          else{
          ?>
        <main>
          <div class ="form">
              <form action="" method="post" name="reg" >
                  
                  <input id="pb_nm" name="name"  placeholder="User Name" type="text"  required tabindex="1"><br>
                  <input type="password" id='phone' name="phone" placeholder="Phone No. as Password" required tabindex="2"><br>
                  <input name="submit" type="submit" value="Submit" tabindex="3">
              </form>
              
          
          <div>
          <p>Back to <a href='index.php'>Login</a></p>
          </div>
        </main>

          <?php

          }  
      ?>
      
      <footer>2021 Abul, Abdul & Wajid</footer>
  </body>
</html>
