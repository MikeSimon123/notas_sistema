<?php
    $host = "localhost";
    $db = "gerenciadordb";
    $user = "work";
    $senha = "senha";
    try{
        $conexao = new PDO("mysql:host=$host; dbname=$db;", $user, $senha);
    }catch(Exception $erro){
        echo $erro;
    }