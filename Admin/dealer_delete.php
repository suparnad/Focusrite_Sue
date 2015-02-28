<?php
// include database page
include '../database.php';

// create database odject
$database = new database();
?>
<html>
    <head>
        <title>Delete</title>
    </head>
    <body>
        <?php
        $id = $_GET['id']; //get id from url
        try {
            if ($id) {
                $del = $database->delete($id);
                echo $del;
                if ($del == false) {
                    echo mysql_error();
                } else {
                    $rurl = "index.php";
                    header('Location: ' . $rurl . "?success=true");
                }

                //database connection close
                $database->close();
                ?>
                <input type="hidden" name="hdnID" value="<?= $id ?>" />
                <?php
            } else {
                
            }
        } catch (Exception $e) {

            echo 'Caught exception: ', $e->getMessage(), "\n";
            //database connection close
            $database->close();
        }
        ?>
    </body>
</html>