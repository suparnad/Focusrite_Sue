<?php

//set admin username and password
$_user_ = 'admin';
$_password_ = 'admin';

//session start
session_start();

$url_action = (empty($_REQUEST['action'])) ? 'logIn' : $_REQUEST['action'];
$auth_realm = (isset($auth_realm)) ? $auth_realm : '';

if (isset($url_action)) {
    if (is_callable($url_action)) {
        call_user_func($url_action);
    } else {
        echo 'Function does not exist, request terminated';
    };
};


//create a logging function 
function logIn() {
    global $auth_realm;
    
    //create unique token for each important action 
    $id = md5(uniqid(mt_rand(), true));
    $_SESSION['token'] = $id;

    //checking session token. if it is empty then set session token
    function get_token() {
        return (!empty($_SESSION['token'])) ? $_SESSION['token'] : 0;
    }

    if (!isset($_SESSION['username'])) {
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = TRUE;
            header('WWW-Authenticate: Basic realm="' . $auth_realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You must enter a valid login and password';
            echo '<p><a href="?action=logOut">Try again</a></p>';
            exit;
        } else {
            $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
            $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $result = authenticate($user, $password);
            if ($result == 0) {
                $_SESSION['username'] = $user;
            } else {
                session_unset($_SESSION['login']);
                errMes($result);
                echo '<p><a href="">Try again</a></p>';
                exit;
            };
        };
    };
}

//checking admin username and password authentication 
function authenticate($user, $password) {
    global $_user_;
    global $_password_;

    if (($user == $_user_) && ($password == $_password_)) {
        return 0;
    } else {
        return 1;
    };
}

//create a funtion to set error messages
function errMes($errno) {
    switch ($errno) {
        case 0:
            break;
        case 1:
            echo 'The username or password you entered is incorrect';
            break;
        default:
            echo 'Unknown error';
    };
}

//logging out function 
function logOut() {

    session_destroy();

    //checking session username variable.If it has a value then unset variable
    if (isset($_SESSION['username'])) {
        session_unset($_SESSION['username']);
        echo "You've successfully logged out<br>";
        echo '<p><a href="?action=logIn">LogIn</a></p>';
    } else {
        header("Location: ?action=logIn", TRUE, 301);
    };
    if (isset($_SESSION['login'])) {
        session_unset($_SESSION['login']);
    };
    exit;
}

?>