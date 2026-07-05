<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "php/verify.php";?>
    <?php if($_SESSION["func"] == "aluno"){echo "<script>window.location = 'cursos.php'</script>";}?>
    <title>Página de Cursos</title>
</head>
<body>
    <?php include_once "php/menu.php" ?>
    <h1>Administre um curso</h1>
    <section id="cursosAdministro">
        <h2>Cursos que administro</h2>
        <section id="sectionCursosAdministro"></section>
    </section>
    <section id="cadastrarCurso">
        <form action="" method="post" id="formCadastrarCurso">
            <p>Nome do curso:</p> <input type="text" id="nomeCurso">
            <p>Descrição:</p> <textarea name="" id="descCurso"></textarea>
            <input type="submit" value="Cadastrar curso">
        </form>
    </section>

    <script>
        const formCadastrarCurso = document.querySelector("#formCadastrarCurso");
        const nomeCurso = document.querySelector("#nomeCurso");
        const descCurso = document.querySelector("#descCurso");
        const sectionCursosAdministro = document.querySelector("#sectionCursosAdministro");

        async function getCursosAdministro(){
            conexao = new Conexao("getCursosAdministro");
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                sectionCursosAdministro.innerHTML = "";
                cursos = resposta["cursos"];
                for(i=0; i < cursos.length; i++){
                    h3 = document.createElement("h3");
                    h3.innerText = cursos[i]["nome"];
                    p = document.createElement("p");
                    p.innerText = cursos[i]["descricao"];
                    section = document.createElement("section");
                    section.appendChild(h3);
                    section.appendChild(p);
                    sectionCursosAdministro.appendChild(section);
                }
            } else if(resposta["status"] == "semCursos"){
                sectionCursosAdministro.innerHTML = "";
                msg = document.createElement("p")
                msg.innerText = "Nenhum curso administrado por você ainda";
                sectionCursosAdministro.appendChild(msg);
            } else {
                alert("Não foi possível trazer os cursos cadastrados!");
            }
        }
        getCursosAdministro();

        async function cadastrarCurso(nome, descricao){
            conexao = new Conexao("createCurso");
            conexao.addData("nome", nome);
            conexao.addData("nomeTratado", nome.replaceAll(" ", ""));
            conexao.addData("descricao", descricao);
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                alert("Turma criada com sucesso!");
                getCursosAdministro();
            } else if(resposta["status"] == "jaExiste"){
                alert("Esse curso já existe! Use outro nome!");
            } else {
                alert("Não foi possível cadastrar esse curso!");
            }
        }
        formCadastrarCurso.addEventListener("submit", e=>{
            e.preventDefault();
            if(nomeCurso.value == ""){
                alert("Dê um nome para o curso primeiro!");
                window.location = "#nomeCurso";
            } else if(descCurso.value == ""){
                alert("Dê ao menos uma descrição ao curso!");
                window.location = "#descCurso";
            } else {
                cadastrarCurso(nomeCurso.value, descCurso.value);
            }
        })
    </script>
</body>
</html>