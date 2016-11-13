<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // Everything below this point in the file is secured by the login system 
     
    // We can display the user's username to them by reading it from the session array.  Remember that because 
    // a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 
Hello <?php echo htmlentities($_SESSION['user']['login'], ENT_QUOTES, 'UTF-8'); ?>, welcome to the crappy Frontier Panel!<br /> <br>
<a href="http://www.mediafire.com/download/avs5rstaad2mjzh/SystemTestNPv2.zip">CLICK HERE</a> to download the alpha system. <br>
You can also participate in our <a href="http://boards.l2frontier.com">Alpha Forums!</a><br><br>

====MAIN MENU===<br><br>
<a href="edit_account.php">Edit Account</a><br /> 
Character Panel (Coming Soon)<br>
Frontier Vault (Coming Soon)<br>
<a href="logout.php">Logout</a>

<br>
<br>
<br>
<br>

Stuff That Hurts Enterprises LTD.