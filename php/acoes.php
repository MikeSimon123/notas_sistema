<?php
    require_once "connection.php";
    session_start();
    $resposta = ["status" => "falhou"];
    header("Content-Type: application/json");
    $json = file_get_contents("php://input");
    $dados = json_decode($json, true);

    if($dados["comando"] == "entrar"){
        try{
            $login = $dados["dados"]["login"];
            $senha = $dados["dados"]["senha"];
            $comando = $conexao->query("select * from usuarios where user='$login' && senha='$senha'");
            $usuario = $comando->fetch(PDO::FETCH_ASSOC);
            if($usuario == false){
                $resposta = ["status" => "falha"];
            } else {
                $resposta = ["status" => "sucesso"];
                $_SESSION["login"] = $usuario["login"];
                $_SESSION["id"] = $usuario["id"];
                $_SESSION["nome"] = $usuario["nome"];
                $_SESSION["func"] = $usuario["func"];
            }
        }catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => "$erro"];
        }
    }
    if($dados["comando"] == "cadastrar"){
        try{
            $nomeCompleto = $dados["dados"]["nomeCompleto"];
            $dataNasc = $dados["dados"]["dataNasc"];
            $email = $dados["dados"]["email"];
            $tel = $dados["dados"]["tel"];
            $user = $dados["dados"]["user"];
            $senha = $dados["dados"]["senha"];
            $func = "aluno";
            $comando = $conexao->query("insert into usuarios(nome, dataNasc,
            email, tel, user, senha, func) values('$nomeCompleto', '$dataNasc',
            '$email', '$tel', '$user', '$senha', '$func')");
            $resposta = ["status" => "sucesso"];
        } catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => "$erro"];
        }
    }
    

    ob_clean();
    echo json_encode($resposta);
    exit;