<?php
    class Alunos{
        public $nome;
        public $nota1;
        public $nota2;
        public $nota3;
        public $nota4;
        public $media;
        public function __construct($nome, $nota1, $nota2, $nota3, $nota4, $media){
            $this->nome = $nome;
            $this->nota1 = $nota1;
            $this->nota2 = $nota2;
            $this->nota3 = $nota3;
            $this->nota4 = $nota4;
            $this->media = $media;
        }
    }

    function getAlunos($nome){
        $host = "localhost";
        $db = "gerenciadordb";
        $user = "work";
        $senha = "senha";
        $conexao = new PDO("mysql:host=$host; dbname=$db;", $user, $senha);
        $comando = $conexao->query("select nomeTratado from cursos");
        $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
        $cursosArray = [];
        $cursosArrayDefinitivo = [];
        $dadosArray = [];
        foreach($cursos as $curso){
            $nomeTabela = $curso["nomeTratado"] . "tb";
            $comando = $conexao->query("select professor from $nomeTabela where professor = 1 and nome = '$nome'");
            $linha = $comando->fetch(PDO::FETCH_ASSOC);
            if($linha != false){
                array_push($cursosArray, $curso["nomeTratado"]);
            }
        }
        if($cursosArray == []){
            return [];
        } else {
            for($i = 0; $i<count($cursosArray); $i++){
                $nomeTabela = $cursosArray[$i] . "tb";
                $nome = $cursosArray[$i];
                $comando = $conexao->query("select * from $nomeTabela where professor != 1");
                $alunos = $comando->fetchAll(PDO::FETCH_ASSOC);
                $dadosArray = ["$nome"];
                foreach($alunos as $aluno){
                    $alunoObj = new Alunos($aluno["nome"], $aluno["nota1"], $aluno["nota2"], $aluno["nota3"], $aluno["nota4"], $aluno["media"]);
                    array_push($dadosArray, json_encode($alunoObj));
                }
                if($alunos == []){array_push($cursosArrayDefinitivo, "nao tem aluno");}
                array_push($cursosArrayDefinitivo, $dadosArray);
            }
            return $cursosArrayDefinitivo;
        }
    }