<?php
    class Notas {
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
    function getDados() {
        $host = 'localhost';
        $db = 'gerenciadordb';
        $user = 'work';
        $senha = 'senha';
        $conexao = new PDO("mysql:host=$host; dbname=$db;", $user, $senha);
        $comando = $conexao->query("select nome,nomeTratado from cursos");
        $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
        $dadosArray = [];
        foreach($cursos as $curso){
            $nomeTabela = $curso["nomeTratado"] . "tb";
            $id = $_SESSION["id"];
            $comando = $conexao->query("select * from $nomeTabela where identificacao = '$id'");
            $dado = $comando->fetch(PDO::FETCH_ASSOC);
            if($dado != false){
                $dado = new Notas($curso["nome"], $dado["nota1"], $dado["nota2"], $dado["nota3"], $dado["nota4"], $dado["media"]);
                array_push($dadosArray, json_encode($dado));
            }
        }
        return $dadosArray;
    }
    