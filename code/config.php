<?php

// function to catch exceptions in text file for debugging
function catchErrorToFile($message ,$code = null){
    try {
        $file = fopen('err.txt','a');
        fwrite($file , "Code : $code \n Message : $message \n". date('Y-m-d h:i:s a') ."\n ---------------- \n");
        fclose($file);
    } catch (Exception $e) {
            
    }
}

// Connect to Database
function pdo_connect(){
    try{
        $connect = new PDO('mysql:host=localhost;dbname=house_wallet_system', "root", "");
        return $connect;
    }catch(PDOException $e){
        catchErrorToFile($e->getMessage(),$e->getCode());
        return null;
    }
}

// redirecting to a page
function redirect_page($page_name){
     header('location:' . $page_name);
     die('exit');
}