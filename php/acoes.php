<?php
    require_once "connection.php";
    session_start();
    require_once "Notas.php";
    require_once "Alunos.php";
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
    if($dados["comando"] == "sair"){
        try {
            session_destroy();
            $resposta = ["status" => "sucesso"];
        }catch (Exception){
            $resposta = ["status" => "erro"];
        }
    }
    if($dados["comando"] == "getCursos"){
        try {
            $comando = $conexao->query("select nome, nomeTratado from cursos");
            $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
            if($cursos != []){
                $resposta = ["status" => "sucesso", "cursos" => $cursos];
            } else {
                $resposta = ["status" => "semCursos"];
            }
        } catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => $erro];
        }
    }
    if($dados["comando"] == "insertCurso"){
        try {
            $tabela = $dados["dados"]["tabela"];
            $nome = $_SESSION["nome"];
            $id = $_SESSION["id"];
            $comando = $conexao->query("insert into $tabela(nome, identificacao, professor)
            values('$nome', '$id', '0')");
            $resposta = ["status" => "sucesso"];
        } catch(Exception){
            $resposta = ["status" => "erro"];
        }
    }
    if($dados["comando"] == "getCursosCadastrados"){
        try {
            $id = $_SESSION["id"];
            $comando = $conexao->query("select nome, nomeTratado, descricao from cursos");
            $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
            $cursosArray = [];
            foreach($cursos as $curso){
                $tabela = $curso["nomeTratado"] . "tb";
                $comando = $conexao->query("select identificacao from $tabela where identificacao = '$id'");
                $aluno = $comando->fetch(PDO::FETCH_ASSOC);
                if($aluno != false){
                    array_push($cursosArray, $curso);
                }
            }
            if($cursosArray == []){
                $resposta = ["status" => "semCursos"];
            } else {
                $resposta = ["status" => "sucesso", "cursos" => $cursosArray];
            }
        } catch(Exception){
            $resposta = ["status" => "erro"];
        }
    }
    if($dados["comando"] == "createCurso"){
        try {
            $nome = $dados["dados"]["nome"];
            $nomeTratado = $dados["dados"]["nomeTratado"];
            $descricao = $dados["dados"]["descricao"];
            $nomeTabela = $nomeTratado . "tb";
            $nomeProfessor = $_SESSION["nome"];
            $comando = $conexao->query("select nomeTratado from cursos where nomeTratado = '$nomeTratado'");
            $curso = $comando->fetch(PDO::FETCH_ASSOC);
            if($curso != false){
                $resposta = ["status" => "jaExiste"];
            } else {
                $comando = $conexao->query("create table $nomeTabela(
                    id int auto_increment primary key,
                    nome varchar(100),
                    identificacao int,
                    nota1 float,
                    nota2 float,
                    nota3 float,
                    nota4 float,
                    media float,
                    professor int
                )");
                $comando = $conexao->query("insert into cursos(nome, nomeTratado, descricao) values(
                '$nome', '$nomeTratado', '$descricao')");
                $comando = $conexao->query("insert into $nomeTabela(nome, professor) values('$nomeProfessor', '1')");
                $resposta = ["status" => "sucesso"];
            }
        } catch(Exception){
            $resposta = ["status" => "erro"];
        }
    }
    if($dados["comando"] == "getCursosAdministro"){
        try {
            $nome = $_SESSION["nome"];
            $comando = $conexao->query("select nome, nomeTratado, descricao from cursos");
            $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
            $cursosArray = [];
            foreach($cursos as $curso){
                $nomeTabela = $curso["nomeTratado"] . "tb";
                $comando = $conexao->query("select nome from $nomeTabela where professor = 1");
                $professor = $comando->fetch(PDO::FETCH_ASSOC);
                if($professor != false){
                    array_push($cursosArray, $curso);
                }
            }
            if($cursosArray == []){
                $resposta = ["status" => "semCursos"];
            } else {
                $resposta = ["status" => "sucesso", "cursos" => $cursosArray];
            }
        } catch(Exception){
            $resposta = ["status" => "erro"];
        }
    }
    if($dados["comando"] == "getNotas"){
        try {
            $dadosArray = getDados();
            if($dadosArray == []){
                $resposta = ["status" => "semCursos"];
            } else {
                $resposta = ["status" => "sucesso", "cursos" => $dadosArray];
            }
        } catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => $erro];
        }
    }
    if($dados["comando"] == "getAlunos"){
        try {
            $dadosArray = getAlunos($_SESSION["nome"]);
            if($dadosArray == []){
                $resposta = ["status" => "semCursos"];
            } else {
                $resposta = ["status" => "sucesso", "cursos" => $dadosArray];
            }
        } catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => "$erro"];
        }
    }
    if($dados["comando"] == "editarDadosBanco"){
        try {
            $tabela = $dados["dados"]["tabela"] . "tb";
            $n1 = $dados["dados"]["nota1"];
            $n2 = $dados["dados"]["nota2"];
            $n3 = $dados["dados"]["nota3"];
            $n4 = $dados["dados"]["nota4"];
            $nt1 = 0; $nt2 = 0; $nt3 = 0; $nt4 = 0;
            if($n1 == ""){$nt1 = 0;} else { $nt1 = (float)$n1; $n1 = "nota1='$n1',";}
            if($n2 == ""){$nt2 = 0;} else { $nt2 = (float)$n2; $n2 = "nota2='$n2',";}
            if($n3 == ""){$nt3 = 0;} else { $nt3 = (float)$n3; $n3 = "nota3='$n3',";}
            if($n4 == ""){$nt4 = 0;} else { $nt4 = (float)$n4; $n4 = "nota4='$n4',";}
            $media = ($nt1+$nt2+$nt3+$nt4)/4;
            $nomeAluno = $dados["dados"]["nomeAluno"];
            $comando = $conexao->query("update $tabela set $n1 $n2 $n3 $n4 media = '$media' where nome = '$nomeAluno'");
            $resposta = ["status" => "sucesso"];
        } catch(Exception $erro){
            $resposta = ["status" => "erro", "erro" => "$erro"];
        }
    }
    

    ob_clean();
    echo json_encode($resposta);
    exit;