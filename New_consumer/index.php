<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css" />
        
        
        <title>Consumer</title>
    </head>
    <header>
        <h1>Hi, You can Send your request for Message to ESB.</h1>
    </header>
    <body>
        <main>
        <?php
            function XML2Array(SimpleXMLElement $parent)
            {
                $array = array();
            
                foreach ($parent as $name => $element) {
                    ($node = & $array[$name])
                        && (1 === count($node) ? $node = array($node) : 1)
                        && $node = & $node[];
            
                    $node = $element->count() ? XML2Array($element) : trim($element);
                }
            
                return $array;
            }
            $output="";
            if(isset($_POST['submit'])){
                
                $js='{"con_name":"'.$_POST['con_name'].
                    '","top_name":"'.$_POST['top_name'].
                    '","last_topic":"'.$_POST['last_topic'].
                    '","format":"'.$_POST['formats'].'"}';
                try{
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://localhost:3000/consumer',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$js,
                            CURLOPT_HTTPHEADER => array(
                              'Content-Type: application/json'
                            ),
                        ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $output=($response);
                    
                }
                catch (Exception $e) {
                    $output= $e->getMessage();
                }
                unset($_POST['submit']);
            }

        ?>
            <div class ="form">
                <form action="" method="POST">
                    <div>
                        <label for="txtConName">Consumer Name:</label>
                        <input type="text" id='con_name' name="con_name" required >
                    </div>    
                    <div>
                        <label for="txttopName">Topic Name:</label>
                        <input type="text" id='top_name' name="top_name" required >
                    </div>                                
                    <div>
                        <label for="txtmsg">The number of the last Topic you have:</label>
                        <input type="number" name="last_topic" id='last_top' required></textarea>
                    </div>
                    <label for="type">Choose your data type:</label> 
                    <select id="formats" name="formats" required >
                        <option value="json">JSON</option>
                        <option value="xml">XML</option>
                        <option value="csv">CSV</option>
                        <option value="tsv">TSV</option>
                    </select><br>                                       
                    <input type="submit" id="submit" name="submit" value="Submit">
                </form>
            </div>
            <div id="aftersubmit" class="<?php echo($output=="") ?'u-hidden': 'u-visible'; ?>">
                <fieldset id = "result">
                    <legend >Response of Your last request</legend>
                    <p><?php echo "'".$output."'"; ?></p>
                </fieldset>
            </div>
        </main>
        <footer>2021 Abul, Abdul & Wajid.<?php echo (isset($_POST['con-name'] )); ?></footer>
    </body>
</html>
