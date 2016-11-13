<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
    //require ("phpmailer/PHPMailerAutoload.php");
    //require ("autoload.php");
    
    $welcome_email = $_POST['email'];
    //ini_set('display_errors', 'On');
    //error_reporting(E_ALL);
    
    
     
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

.shrink-text {
    width:80%;
    margin-right:auto;
    margin-left:auto;
}
</style>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>

<body>
<div class="services-bg"></div>
<div class="logo-top"><div class="lineage-logo"></div><div class="frontier-logo"></div></div>
<div class="services-container">
    <div class="shrink-text">
    <h1>Almost Done!</h1>
    <h3>As a security measure we require you confirm your account registration, we've sent you a confirmation mail to your registered e-mail adress <b><?php echo $welcome_email; ?></b>. Make sure to check your spam folder if you cannot find the mail on your inbox.</h3>
    </div>
    </div>
</body>