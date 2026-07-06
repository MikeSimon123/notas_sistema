<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "php/verify.php";?>
    <?php if($_SESSION["func"] == "professor"){echo "<script>window.location = 'cursosProfessor.php'</script>";}?>
    <link rel="stylesheet" href="css/style.css">
    <title>Página de cursos</title>
</head>
<body>
    <section id='menu'><?php include_once "php/menu.php";?></section>
    <h1>Inscreva-se em um curso</h1>
    <section id="cursosCadastrados">
        <h2>Cursos em que estou cadastrado</h2>
        <section id="sectionCursosCadastrados"></section>
    </section>
    <section id="cursos">
        <h2>Cadastrar-se em um curso</h2>
        <form action="" method="post" id="formCursos">
            <select id="selectCursos">
                <option value="default">Selecione um curso</option>
            </select>
            <input type="submit" value="Cadastrar-se">
        </form>
    </section>

    <script>
        const formCursos = document.querySelector("#formCursos");
        const selectCursos = document.querySelector("#selectCursos");
        const sectionCursosCadastrados = document.querySelector("#sectionCursosCadastrados")

        async function getCursos(){
            conexao = new Conexao("getCursos");
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                cursos = resposta["cursos"];
                selectCursos.innerHTML = "<option value='default'>Selecione um curso</option>";
                for(i=0; i<cursos.length; i++){
                    optionCurso = document.createElement("option");
                    optionCurso.value = cursos[i]["nome"];
                    optionCurso.innerText = cursos[i]["nomeTratado"];
                    selectCursos.appendChild(optionCurso);
                }
            } else if(resposta["status"] == "semCursos") {
                selectCursos.innerHTML = "<option value='default'>Selecione um curso</option>";
                msg = document.createElement("p");
                msg.innerText = "Não existem cursos disponíveis no momento";
                formCursos.appendChild(msg);
            }
            else {
                alert("Não foi possível trazer os cursos")
            }
        }
        getCursos();

        async function getCursosCadastrados(){
            conexao = new Conexao("getCursosCadastrados");
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                sectionCursosCadastrados.innerHTML = "";
                cursos = resposta["cursos"];
                for(i=0; i<cursos.length; i++){
                    h3 = document.createElement("h3");
                    h3.innerText = cursos[i]["nome"];
                    p = document.createElement("p");
                    p.innerText = cursos[i]["descricao"];
                    section = document.createElement("section");
                    section.classList.add("curso");
                    section.appendChild(h3);
                    section.appendChild(p);
                    sectionCursosCadastrados.appendChild(section);
                }
            } else if(resposta["status"] == "semCursos"){
                sectionCursosCadastrados.innerHTML = "";
                msg = document.createElement("p");
                msg.innerText = "Nenhum cadastro feito nos cursos ainda";
                sectionCursosCadastrados.appendChild(msg);
            } else {
                alert("Não foi possível trazer os cursos cadastrados");
            }
        }
        getCursosCadastrados();

        async function insertCurso(nomeCurso){
            conexao = new Conexao("insertCurso");
            conexao.addData("tabela", `${nomeCurso}tb`);
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                alert("Cadastrado no curso com sucesso!");
            } else {
                alert("Não foi possível se cadastrar no curso");
            }
        }
        formCursos.addEventListener("submit", e=>{
            e.preventDefault();
            if(selectCursos.value != "default"){
                insertCurso(selectCursos.value)
                getCursosCadastrados();
            } else {
                alert("Selecione um curso válido!");
            }
        })
    </script>
</body>
</html>