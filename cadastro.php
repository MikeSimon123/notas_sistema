<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cadastro</title>
</head>
<body>
    <h1>Página de Cadastro</h1>

    <form action="" method="post" id="formCadastro">
        <p>Nome completo:</p> <input type="text" id="nomeCompleto">
        <p>Data de Nascimento</p> <input type="date" id="dataNasc">
        <p>Email:</p> <input type="email" id="email">
        <p>Telefone</p> <input type="tel" id="tel">
        <p>Nome de Usuário:</p><input type="text" id="user">
        <p>Senha:</p> <input type="password" id="senha">
        <p>Confirme sua senha:</p><input type="password" id="confirmSenha">
        <input type="submit" value="Cadastrar">
    </form>

    <script src="js/conexao.js"></script>
    <script>
        const formCadastro = document.querySelector("#formCadastro");
        const nomeCompleto = document.querySelector("#nomeCompleto");
        const dataNasc = document.querySelector("#dataNasc");
        const email = document.querySelector("#email");
        const tel = document.querySelector("#tel");
        const user = document.querySelector("#user");
        const senha = document.querySelector("#senha");
        const confirmSenha = document.querySelector("#confirmSenha");

        async function cadastrar(){
            conexao = new Conexao("cadastrar");
            conexao.addData("nomeCompleto", nomeCompleto.value);
            conexao.addData("dataNasc", dataNasc.value);
            conexao.addData("email", email.value);
            conexao.addData("tel", tel.value);
            conexao.addData("user", user.value);
            conexao.addData("senha", senha.value);
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                alert("Cadastrado com sucesso!");
                window.location = "index.php"
            } else if(resposta["status"] == "falha"){
                alert("Não foi possível realizar cadastro");
            } else if(resposta["status"] == "erro"){
                alert("ERRO: "+ resposta["erro"]);
            }
        }
        formCadastro.addEventListener("submit", e=>{
            e.preventDefault();
            if(senha.value != confirmSenha.value){
                alert("Campos de senha não estão iguais!");
                window.location = "#confirmSenha";
            } else {
                cadastrar();
            }
        })
    </script>
</body>
</html>