<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "php/verify.php";?>
    <?php if($_SESSION["func"] == "professor"){
        echo "<script>window.location = 'boletimProfessor.php';</script>";
    }?>
    <link rel="stylesheet" href="css/style.css">
    <title>Página de Boletim</title>
</head>
<body>
    <section id='menu'><?php include_once "php/menu.php";?></section>
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
                cursosArr = resposta["cursos"];
                section1 = document.createElement("section");
                tN = document.createElement("h3");
                tN.innerText = "Curso";
                tN1 = document.createElement("h3");
                tN1.innerText = "Nota 1";
                tN2 = document.createElement("h3");
                tN2.innerText = "Nota 2";
                tN3 = document.createElement("h3");
                tN3.innerText = "Nota 3";
                tN4 = document.createElement("h3");
                tN4.innerText = "Nota 4";
                tM = document.createElement("h3");
                tM.innerText = "Média"
                section1.appendChild(tN);
                section1.appendChild(tN1);
                section1.appendChild(tN2);
                section1.appendChild(tN3);
                section1.appendChild(tN4);
                section1.appendChild(tM);
                sectionBoletim.appendChild(section1);
                for(i=0; i<cursosArr.length; i++){
                    cursos = JSON.parse(cursosArr[i]);
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
                    if(cursos["media"] == null){media.innerText = "-"}
                    else {media.innerText = cursos["media"];} 
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