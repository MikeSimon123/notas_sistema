<?php
    if($_SESSION["func"] == "aluno"){
        $cursos = "cursos.php";
    } else if ($_SESSION["func"] == "professor"){
        $cursos = "cursosProfessor.php";
    }
    echo "
        <a href='$cursos'>Cursos</a>
        <a href=''>Boletim</a>
        <input type='button' value='Sair' onclick='sair()'>

        <script src='../js/conexao.js'></script>
        <script>
            async function sair(){
                conexao = new Conexao('sair');
                resposta = await conexao.tryConnection();
                if(resposta['status'] == 'sucesso'){
                    alert('Saindo');
                    window.location = 'index.php';
                } else {
                    alert('Erro ao sair'); 
                }
            }
        </script>
    ";