<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
    require ("phpmailer/PHPMailerAutoload.php");
    require ("autoload.php");
    
    
    //ini_set('display_errors', 'On');
    //error_reporting(E_ALL);
    
    
    // Register API keys at https://www.google.com/recaptcha/admin
    $siteKey = '6LfzjggTAAAAAB8EMk4EHJwWF4Ugp2ecw5jVHEsy';
    $secret = '6LfzjggTAAAAABxRqyNWQc2_NxTqQZfTusoN_TZG';
     
    // This if statement checks to determine whether the registration form has been submitted 
    // If it has, then the registration code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['username'])) 
        { 
            // Note that die() is generally a terrible way of handling user errors 
            // like this.  It is much better to display the error with the form 
            // and allow the user to correct their mistake.  However, that is an 
            // exercise for you to implement yourself. 
                     
            //print("Please enter a username."); 
            die("Please enter a username."); 
            
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
        
        
        //reCaptcha stuff goes here!
        
        //ensure the user is not a robot...
        
        if(isset($_POST['g-recaptcha-response'])) 
        { 
            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            
            if ($resp->isSuccess()){
                //OK
            }else{
                die("reCaptcha not checked.."); 
            }
            
        } 
        
        
        
        
        
        ////////////////////////////
         
        // Make sure the user entered a valid E-Mail address 
        // filter_var is a useful PHP function for validating form input, see: 
        // http://us.php.net/manual/en/function.filter-var.php 
        // http://us.php.net/manual/en/filter.filters.php 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // We will use this SQL query to see whether the username entered by the 
        // user is already in use.  A SELECT query is used to retrieve data from the database. 
        // :username is a special token, we will substitute a real value in its place when 
        // we execute the query. 
        $query = " 
            SELECT 
                1 
            FROM accounts 
            WHERE 
                login = :login 
        "; 
         
        // This contains the definitions for any special tokens that we place in 
        // our SQL query.  In this case, we are defining a value for the token 
        // :username.  It is possible to insert $_POST['username'] directly into 
        // your $query string; however doing so is very insecure and opens your 
        // code up to SQL injection exploits.  Using tokens prevents this. 
        // For more information on SQL injections, see Wikipedia: 
        // http://en.wikipedia.org/wiki/SQL_Injection 
        $query_params = array( 
            ':login' => $_POST['username'] 
        ); 
         
        try 
        { 
            // These two statements run the query against your database table. 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // The fetch() method returns an array representing the "next" row from 
        // the selected results, or false if there are no more rows to fetch. 
        $row = $stmt->fetch(); 
         
        // If a row was returned, then we know a matching username was found in 
        // the database already and we should not allow the user to continue. 
        if($row) 
        { 
            die("This username is already in use"); 
        } 
         
        // Now we perform the same type of check for the email address, in order 
        // to ensure that it is unique. 
        $query = " 
            SELECT 
                1 
            FROM accounts 
            WHERE 
                email = :email 
        "; 
         
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        } 
        
         
        // An INSERT query is used to add new rows to a database table. 
        // Again, we are using special tokens (technically called parameters) to 
        // protect against SQL injection attacks. 
        $query = " 
            INSERT INTO accounts ( 
                login, 
                password, 
                email,
                created_time,
                lastactive,
                accessLevel,
                lastIP,
                lastServer,
                pcIp,
                hop1,
                hop2,
                hop3,
                hop4,
                game_points
            ) VALUES ( 
                :login, 
                :password, 
                :email,
                :created_time,
                :lastactive,
                :accessLevel,
                :lastIP,
                :lastServer,
                :pcIp,
                :hop1,
                :hop2,
                :hop3,
                :hop4,
                :game_points
            ) 
        "; 
         
        

         
        // This hashes the password with the salt so that it can be stored securely 
        // in your database.  The output of this next statement is a 64 byte hex 
        // string representing the 32 byte sha256 hash of the password.  The original 
        // password cannot be recovered from the hash.  For more information: 
        // http://en.wikipedia.org/wiki/Cryptographic_hash_function 
        
        $normalPass = $_POST['password'];
        
        $password = base64_encode(sha1($normalPass, true));
        $default_level = 20;
       
        //Here we add the date variable
        $creationdate = date('Y-m-d');
         
        // Here we prepare our tokens for insertion into the SQL query.  We do not 
        // store the original password; only the hashed version of it.  We do store 
        // the salt (in its plaintext form; this is not a security risk). 
        $query_params = array( 
            ':login' => $_POST['username'], 
            ':password' => $password,  
            ':email' => $_POST['email'],
            ':created_time' => $creationdate,
            ':lastactive' => '0',
            ':accessLevel' => $default_level,
            ':lastIP' => null,
            ':lastServer' => 1,
            ':pcIp' => null,
            ':hop1' => null,
            ':hop2' => null,
            ':hop3' => null,
            ':hop4' => null,
            ':game_points' => 0
            
        ); 
        

        try 
        { 
            // Execute the query to create the user 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
        
        
        ////////////////////////EMAIL AREA/////////////////////////////////////
        
        /**
         * This example shows settings to use when sending via Google's Gmail servers.
         */

        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');

        $domain_name = "www.l2frontier.com";
        $account_name =$_POST['username'];
        $account_email = $_POST['email'];



        //Create a new PHPMailer instance
        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "admin@l2frontier.com";

        //Password to use for SMTP authentication
        $mail->Password = "TaiyouS@isei";

        //Set who the message is to be sent from
        $mail->setFrom('noreply@l2frontier.com', 'Lineage II Frontier');

        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');

        //Set who the message is to be sent to
        $mail->addAddress($account_email, $account_name);

        //Set the subject line
        $mail->Subject = 'Activate your Account';



        $embedded_message = '
        <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <title>Lineage II Frontier Account Activation</title>
        </head>
        <body>
        <div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
          <h1>Welcome to Lineage II Frontier</h1>
          <h2>In order to begin playing in our server we need you confirm your game account first by clicking on the link below</h2>

            <h1><a href="http://'.$domain_name.'/verify.php?account='.$account_name.'&key='.$password.'&email='.$account_email.'&al='.$default_level.'">Activate My Account!</a></h1>

          <p>Remember: Your account is only yours, and we held no responsability if you lose it. We suggest you set a unique password as well.</p>
        </div>
        </body>
        </html>

                ';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($embedded_message);

        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        $mail->send();

        
        ////////////////////////////////////////////////////////////////////////
     
        
         
        // This redirects the user back to the login page after they register 
        header("Location: welcome.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to welcome.php"); 
    } 
     
?> 

<head>
<title>Lineage II Frontier Members Site</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/services.css">
<style>

    
    body {
padding:0px;
margin:0px;
  background-color: #000;
}

.services-bg {
    background-image: url('../img/shillenbg.jpg');
    width: 100%;
    height: 100%;
    position: absolute;
    background-size: cover;
    top: 0px;
    left: 0px;
}

.services-container {
    position: absolute;
    width: 100%;
    color: white;
    background-color: rgba(0, 0, 0, 0.45);
    margin-top: 11%;
    box-shadow: 1px 1px 20px 6px rgba(0, 0, 0, 0.69);
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.logo-top {
    position: absolute;
    float: left;
    top: 30px;
    width: 100%;
    height: 140px;

}

.frontier-logo {
        background-image: url('../img/frontierLogo.png');
    
        /* background-size: contain; */
    background-repeat:no-repeat;
    position:absolute;
    
        background-position: 40% 60%;
        background-size: 100%;
        top: -117px;
    
        right: 2%;
    
        -webkit-filter: sepia(1);
        -moz-filter:sepia(1);
        width: 370px;
    
        height: 280px;


        font-style: italic;
        font-size: 70px;
        line-height: 55px;
        color: wheat;
}

.lineage-logo {
    background-image: url('../img/L2_logo_white.png');
    background-size: contain;
    background-repeat:no-repeat;
    position:absolute;
    top:0px;
    left: 60px;
    width:400px;
    height: 60px;


}
</style>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>

<body>
<div class="services-bg"></div>
<div class="logo-top"><div class="lineage-logo"></div><div class="frontier-logo"></div></div>
<div class="services-container">
<form action="register.php" method="post" class="form-signin"> 
    <h2 class="form-signin-heading" style="text-align:center;">Account Registration</h2>
    <input type="text" name="username" value="" class="form-control" placeholder="Username"/> 
    <input type="text" name="email" value="" class="form-control" placeholder="Email"/> 
    <input type="password" name="password" value="" class="form-control" placeholder="Password" /> 
    <br /><br /> 
    <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <br><br>
    <button class="btn btn-lg btn-danger btn-block" type="submit">Register</button>
</form>
</div>
</body>