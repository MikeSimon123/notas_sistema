<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "php/verify.php";?>
    <?php if($_SESSION["func"] == "professor"){
        echo "<script>window.location = 'boletimProfessor.php';</script>";
    }?>
    <title>Página de Boletim</title>
</head>
<body>
    <?php include_once "php/menu.php";?>
    <h1>Veja o seu boletim</h1>
    <section id="boletim">
        <h2>Confira suas notas</h2>
        <section id="sectionBoletim"></section>
    </section>

    <script>
        const sectionBoletim = document.querySelector("#sectionBoletim");

        async function getNotas(){
            conexao = new Conexao("getNotas");
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                sectionBoletim.innerHTML = "";
                cursos = resposta["cursos"];
                for(i=0; i<cursos.length; i++){
                    cursos = JSON.parse(cursos[i]);
                    title = document.createElement("p");
                    title.innerText = cursos["nome"];
                    n1 = document.createElement("p");
                    if(cursos["nota1"] == null){n1.innerText = "-"}
                    else {n1.innerText = cursos["nota1"];}     
                    n2 = document.createElement("p");
                    if(cursos["nota2"] == null){n2.innerText = "-"}
                    else {n2.innerText = cursos["nota2"];} 
                    n3 = document.createElement("p")
                    if(cursos["nota3"] == null){n3.innerText = "-"}
                    else {n3.innerText = cursos["nota3"];} 
                    n4 = document.createElement("p");
                    if(cursos["nota4"] == null){n4.innerText = "-"}
                    else {n4.innerText = cursos["nota4"];} 
                    media = document.createElement("p");
                    if(cursos["meida"] == null){media.innerText = "-"}
                    else {meida.innerText = cursos["meida"];} 
                    section = document.createElement("section");
                    section.appendChild(title);
                    section.appendChild(n1);
                    section.appendChild(n2);
                    section.appendChild(n3);
                    section.appendChild(n4);
                    section.appendChild(media);
                    sectionBoletim.appendChild(section);
               }
            }
            else if(resposta["status"] == "semCursos"){
                sectionBoletim.innerHTML = "";
                msg = document.createElement("p");
                msg.innerText = "Nenhum cadastro feito nos cursos ainda";
                sectionBoletim.appendChild(msg);
            } else if(resposta["status"] == "erro"){
                alert("Não foi possível trazer as suas notas: "+ JSON.stringify(resposta["erro"]));
            }
        }
        getNotas();
    </script>
</body>
</html>