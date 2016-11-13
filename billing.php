<?php
    ini_set('display_errors', 'On');
error_reporting(E_ALL);
        // First we execute our common code to connection to the database and start the session 
        require("common.php"); 
        require "system/config.php";
        //require "system/connect.php";

        // At the top of the page we check to see whether the user is logged in or not 
        if(empty($_SESSION['user'])) 
        { 
            // If they are not, we redirect them to the login page. 
            header("Location: login.php"); 

            // Remember that this die statement is absolutely critical.  Without it, 
            // people can view your members-only content without logging in. 
            die("Redirecting to login.php"); 
        } 
        
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
        // array is stale; we need to update it so that it is accurate. 
        //$_SESSION['user']['email'] = $_POST['email']; 
        
        $myPointsQuery = "SELECT game_points FROM accounts WHERE login=:login";
        
        $query_params = array(
            ':login' => $_SESSION['user']['login']
        );
        
        // Execute the query 
        $stmt = $db->prepare($myPointsQuery); 
        $result = $stmt->execute($query_params); 
        
        $row = $stmt->fetchAll();
        
        
        
        

        
        
        
        
        
        
        
	?>
</head>
<body>
    Hello <?php echo $_SESSION['user']['login'] ?> welcome to the donation page!<br><br><br>
    
            
        <form action="test" method="post">
            Your current Balance is: <b><?php echo $row[0]['game_points'] ?> Mouse Points</b>.<br>
            <input type="submit" name="submit" value="Charge More Points">
        </form>    
    
    <br><br>

    
  <div id="loginsuccess">
    <!-- oke now lets show the donation options -->
    <!-- The PayPal coins Donation option list -->
    <form action="<?php echo $payPalURL?>" method="post" class="payPalForm">
    <input type="hidden" name="cmd" value="_donations" />
    <input type="hidden" name="item_name" value="Donation" />
    <!-- custom field that will be passed to paypal -->
    <input type="hidden" name="custom" value="Lineage II Frontier">

    <!-- Your PayPal email -->
    <input type="hidden" name="business" value="<?php echo $myPayPalEmail?>" />
    <!-- PayPal will send an IPN notification to this URL -->
    <input type="hidden" name="notify_url" value="system/ipn/ipn_coins.php" />
    <!-- The return page to which the user is navigated after the donations is complete -->
    <input type="hidden" name="return" value="done.php" />
    <!-- Signifies that the transaction data will be passed to the return page by POST -->
    <input type="hidden" name="rm" value="2" />

    <!-- Player logged in successfully and the character is logged out -->
    

    <!-- General configuration variables for the paypal landing page. Consult -->
    <!-- http://www.paypal.com/IntegrationCenter/ic_std-variable-ref-donate.html for more info -->
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="cbt" value="Go Back To The Site" />
    <input type="hidden" name="no_shipping" value="1" />
    <input type="hidden" name="lc" value="US" />
    <input type="hidden" name="currency_code" value="<?php echo $currency_code?>" />

    <!-- here the amount of the coins donation can be configured visible html only -->
    
    

    
    
    If you wish to add more points to your balance please enter the desired donation amount.<br>
    Remember that donating 1 euro = 100 Mouse Points<br><br>
    <input type="text" name="amount" style="width: 150px"><br>
                  

    <!-- Here you can change the image of the coins donation button  -->
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
    <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
    <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest" />
    </form>
    <br>
    <br>
    <br>
</div>