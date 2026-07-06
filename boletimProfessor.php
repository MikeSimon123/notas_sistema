<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "php/verify.php";?>
    <?php if($_SESSION["func"] == "aluno"){
        echo "<script>window.location = 'boletim.php';</script>";
    }?>
    <link rel="stylesheet" href="css/style.css">
    <title>Página de Boletim</title>
</head>
<body>
    <section id='menu'><?php include_once "php/menu.php";?></section>
    <h1>Edite as notas dos seus alunos</h1>
    <section id="boletim">
        <h2>Edite as notas dos seus alunos</h2>
        <section id="cursos"></section>
    </section>
    <section id="editarNotas">
        <h2 id='editarNotasTitle'>Editando notas de: </h2>
    </section>
    <script>
        const cursos = document.querySelector("#cursos");
        const editarNotas = document.querySelector("#editarNotas");
        async function editarDadosBanco(nomeAl){
            const n1 = document.querySelector("#editarNota1");
            const n2 = document.querySelector("#editarNota2");
            const n3 = document.querySelector("#editarNota3");
            const n4 = document.querySelector("#editarNota4");
            const t = document.querySelector("#editarTitle");
            tabela = t.innerText.replaceAll(" ", "");

            conexao = new Conexao("editarDadosBanco");
            conexao.addData("nota1", (n1.value != "") ? n1.value : null);
            conexao.addData("nota2", (n2.value != "") ? n2.value : null);
            conexao.addData("nota3", (n3.value != "") ? n3.value : null);
            conexao.addData("nota4", (n4.value != "") ? n4.value : null);
            conexao.addData("nomeAluno", nomeAl);
            conexao.addData("tabela", tabela);
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                alert("Notas editadas com sucesso!");
                editarNotas.style.display = "none";
                getAlunos();
            } else {
                alert("Não foi possível editar as notas"+resposta["erro"]);
            }
        }
        editarNotas.style.display = "none";
        function editarDados(nomeAl, tabela, n1, n2, n3, n4){
            editarNotas.innerHTML = "";
            editarNotas.style.display = "flex";
            editarNotasTitle = document.createElement("h2");
            editarNotasTitle.innerText = "Editando notas de: "+nomeAl;
            nota1 = document.createElement("p");
            nota1.innerText = "Nota 1º Bimestre atual: "
            nota1.innerText += (n1 != null) ? n1 : "-";
            nota2 = document.createElement("p");
            nota2.innerText = "Nota 2º Bimestre atual: "
            nota2.innerText += (n2 != null) ? n2 : "-";
            nota3 = document.createElement("p");
            nota3.innerText = "Nota 3º Bimestre atual: "
            nota3.innerText += (n3 != null) ? n3 : "-";
            nota4 = document.createElement("p");
            nota4.innerText = "Nota 4º Bimestre atual: "
            nota4.innerText += (n4 != null) ? n4 : "-";
            editarNota1 = document.createElement("input");
            editarNota1.id = "editarNota1";
            editarNota1.value = (n1 != null) ? n1 : "";
            editarNota2 = document.createElement("input");
            editarNota2.id = "editarNota2";
            editarNota2.value = (n2 != null) ? n2 : "";
            editarNota3 = document.createElement("input");
            editarNota3.id = "editarNota3";
            editarNota3.value = (n3 != null) ? n3 : "";
            editarNota4 = document.createElement("input");
            editarNota4.id = "editarNota4";
            editarNota4.value = (n4 != null) ? n4 : "";
            nomeCurso = document.createElement("p");
            nomeCurso.id = "editarTitle";
            nomeCurso.innerText = tabela;
            editar = document.createElement("input");
            editar.type = "button";
            editar.value = "Editar";
            editar.setAttribute("onclick", `
                editarDadosBanco('${nomeAl}');
            `)
            cancelar = document.createElement("input");
            cancelar.type = "button";
            cancelar.value = "Cancelar";
            cancelar.setAttribute("onclick", `
                editarNotas.style.display = "none";
            `)
            section1 = document.createElement("section");
            section1.id = "sectionEditarNotas"
            editarNotas.appendChild(editarNotasTitle);
            editarNotas.appendChild(nomeCurso);
            section1.appendChild(nota1); section1.appendChild(editarNota1);
            section1.appendChild(nota2); section1.appendChild(editarNota2);
            section1.appendChild(nota3); section1.appendChild(editarNota3);
            section1.appendChild(nota4); section1.appendChild(editarNota4);
            
            editarNotas.appendChild(section1);
            editarNotas.appendChild(cancelar);
            editarNotas.appendChild(editar);
        }
        async function getAlunos(){
            conexao = new Conexao("getAlunos");
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                cursos.innerHTML = "";
                cursosArray = resposta["cursos"];
                cursosSection = document.createElement("section");
                for(i = 0; i<cursosArray.length; i++){
                    nome = cursosArray[i][0];
                    alunos = [];
                    section = document.createElement("section");
                    nomeTitle = document.createElement("h2");
                    nomeTitle.innerText = nome;
                    section.appendChild(nomeTitle)
                    for(j = 1; j<cursosArray[i].length; j++){
                        alunos.push(JSON.parse(cursosArray[i][j]));
                    }
                    if(alunos == []){
                        msg = document.createElement("p")
                        msg.innerText = "Sem alunos no curso";
                        section.appendChild(msg);
                    } else {
                        for(h = 0; h<alunos.length; h++){
                            nomeAluno = document.createElement("p");
                            nomeAluno.innerText = alunos[h]["nome"];
                            editar = document.createElement("input");
                            editar.id = "editar" + alunos[h]["nome"];
                            editar.value = "Editar";
                            editar.type = "button";
                            nomeDoAluno = alunos[h]["nome"];
                            nomeDaTabela = cursosArray[i][0];
                            n1 = alunos[h]["nota1"];
                            n2 = alunos[h]["nota2"];
                            n3 = alunos[h]["nota3"];
                            n4 = alunos[h]["nota4"];
                            editar.setAttribute("onclick", `editarDados('${nomeDoAluno}', '${nomeDaTabela}', ${n1}, ${n2}, ${n3}, ${n4})`)
                            nota1 = document.createElement("p");
                            if(alunos[h]["nota1"] != null){
                                nota1.innerText = alunos[h]["nota1"];
                            } else {nota1.innerText = "-"}
                            nota2 = document.createElement("p");
                            if(alunos[h]["nota2"] != null){
                                nota2.innerText = alunos[h]["nota2"];
                            } else {nota2.innerText = "-"}
                            nota3 = document.createElement("p");
                            if(alunos[h]["nota3"] != null){
                                nota3.innerText = alunos[h]["nota3"];
                            } else {nota3.innerText = "-"}
                            nota4 = document.createElement("p");
                            if(alunos[h]["nota4"] != null){
                                nota4.innerText = alunos[h]["nota4"];
                            } else {nota4.innerText = "-"}
                            media = document.createElement("p");
                            if(alunos[h]["media"] != null){
                                media.innerText = alunos[h]["media"];
                            } else {media.innerText = "-"}
                            section2 = document.createElement("section");
                            section2.appendChild(nomeAluno);
                            section2.appendChild(nota1);
                            section2.appendChild(nota2);
                            section2.appendChild(nota3);
                            section2.appendChild(nota4);
                            section2.appendChild(media);
                            section2.appendChild(editar);
                            section.appendChild(section2);
                        }
                    }
                    cursosSection.appendChild(section);
                }
                cursos.appendChild(cursosSection);
            } else if(resposta["status"] == "semCursos"){
                cursos.innerHTML = "";
                msg = document.createElement("p");
                msg.innerText = "Nenhum curso administrado por você";
                cursos.appendChild(msg);
            } else {
                alert("Não foi possível trazer os dados dos seus cursos"+resposta['erro']);
            }
        }
        getAlunos();
    </script>
</body>
</html>