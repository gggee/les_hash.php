<?php
    define("HOST", "localhost");
    define("DATABASE", "classicmodels");
    define("CHARSET", "utf8");
    define("USER", "root");
    define("PASSWORD", "");

    function conn(){
        $pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER,
            PASSWORD
        );
    }
    
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $salt = randString(40);
        $sql = "INSERT INTO users (salt, email, password, customerId) VALUES (?, ?, ?, ?)";
        $params = array($salt, $_POST["email"], $_POST["password"], $_POST["customerId"]);
        $pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER,
            PASSWORD
        );
        $result = $pdo->prepare($sql);
        $result->execute($params);
        $userId = $pdo->lastInsertId();
    } else {
        echo randString(40);
        include("addUser.html");
    }

    if(isset($_POST["btn_logIn"])) {
        $login = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT salt, password FROM users WHERE email = ?";
        $params = array($login);
        $result = $pdo->prepare($sql);
        $result->execute($params);
    }

    function randString($length = 32){
        $characters = "0123456789abcdef";
        $randString = "";
        for ($i = 0; $i < $length; $i++) {
            $randString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randString;
    }

    function logs($userId, $actionId){
        $sql = "INSERT INTO logs (userId, actionId, datetime) VALUES (?, ?, NOW())";
        $params = array($userId, $actionId);
        $pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER,
            PASSWORD
        );
        $result = $pdo->prepare($sql);
        $result->execute($params);
    }

    function actions($action_name){
        $sql = "INSERT INTO actions(name) VALUES(?)";
        $params = array($action_name);
        $pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER,
            PASSWORD
        );
        $result = $pdo->prepare($sql);
        $result->execute($params);

        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row["actionId"];
        }else{
            return actions($action_name);
        }
    }

    function log_actionName($action){
        $sql = "SELECT name FROM actions WHERE name=?";
        $params = array($action);
        $pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER,
            PASSWORD
        );
        $result = $pdo->prepare($sql);
        $result->execute($params);
    }

