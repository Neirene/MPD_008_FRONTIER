<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
     
    // Everything below this point in the file is secured by the login system 
     
    // We can retrieve a list of members from the database using a SELECT query. 
    // In this case we do not have a WHERE clause because we want to select all 
    // of the rows from the database table. 
    $query = " 
        SELECT 
            char_name
        FROM characters
        WHERE online=1
    "; 
     
    try 
    { 
        // These two statements run the query against your database table. 
        $stmt = $db_game->prepare($query); 
        $stmt->execute(); 
    } 
    catch(PDOException $ex) 
    { 
        // Note: On a production website, you should not output $ex->getMessage(). 
        // It may provide an attacker with helpful information about your code.  
        die("Failed to run query: " . $ex->getMessage()); 
    } 
         
    // Finally, we can retrieve all of the found rows into an array using fetchAll 
    $rows = $stmt->fetchAll(); 
?> 
<h1>Online Friends</h1> 
<table> 
    <?php foreach($rows as $row): ?> 
        <tr> 
            <td><?php echo htmlentities($row['char_name'], ENT_QUOTES, 'UTF-8'); ?></td>  
        </tr> 
    <?php endforeach; ?> 
</table> 