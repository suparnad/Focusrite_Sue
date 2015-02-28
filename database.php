<?php

class database {

    var $host;
    var $user;
    var $pass;
    var $database;
    var $conn;
    var $query;
    var $dealername;
    var $address;
    var $phone;
    var $fax;
    var $url;
    var $result;
    var $id;
    var $row;
    var $get_id;

    function set_value() {
        $this->dealername = $_POST['DealerName'];
        $this->address = $_POST['DealerAddress'];
        $this->phone = $_POST['Telephone'];
        $this->fax = $_POST['Fax'];
        $this->url = $_POST['Url'];
    }

    //create database connection 
    function __construct() {
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = "";
        $this->database = "focusrite";

        $this->conn = mysql_connect($this->host, $this->user, $this->pass) or die('Could not connect to database server.');
        mysql_select_db($this->database) or die('Could not open database.');
    }

    //fetch specific row from database by dealername or address or phone no or fax or web address
    function search_data($q)
    {
        $this->query = "SELECT * FROM focusrite_dealer_info WHERE DealerName LIKE '%$q%' OR DealerAddress LIKE '%$q%'OR Telephone LIKE '%$q%' OR Fax LIKE '%$q%' OR Url LIKE '%$q%' ORDER BY DealerName LIMIT 25;";
        $this->result = mysql_query($this->query);
        return($this->result);
        
    }
    
    //fetch all data from database
    function fetch_all_data()
    {
        $this->query = "SELECT * FROM focusrite_dealer_info ORDER BY DealerName;";
        $this->result = mysql_query($this->query);
         return($this->result);
    }
    
    //add data into database
    function add() {
        $this->set_value();
        $this->query = "INSERT INTO `focusrite_dealer_info`(`DealerName`, `DealerAddress`, `Telephone`, `Fax`, `Url`) "
                . "VALUES (\"$this->dealername\",\"$this->address\",\"$this->phone\",\"$this->fax\",\"$this->url\")";
        $this->result = mysql_query($this->query);
    }
    
    // fetch edited data from database
    function set_edited_data_value($get_id) {
        $this->query = "SELECT * FROM focusrite_dealer_info WHERE DealerId=$get_id;";
        $this->result = mysql_query($this->query);
        $this->row = mysql_fetch_assoc($this->result);
        return($this->row);
    }
    
    //edit data into database
    function edit() {
        $this->get_id = $_POST['hdnID'];
        $this->set_value();
        $this->query = "UPDATE `focusrite_dealer_info` SET "
                . "DealerName=\"$this->dealername\", DealerAddress=\"$this->address\", Telephone=\"$this->phone\", "
                . "Fax=\"$this->fax\", Url=\"$this->url\""
                . "WHERE DealerId=$this->get_id";
        $this->result = mysql_query($this->query);
        return($this->result);
    }
    
    //delete data from database
    function delete($del_id) {
        $this->query = "DELETE FROM focusrite_dealer_info WHERE DealerId=$del_id;";
        $this->result = mysql_query($this->query);
        return true;
    }

    //to close database connection
    function close() {
        return(mysql_close($this->conn));
    }

}
?>