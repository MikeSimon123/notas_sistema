<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
</head>
<body>
    <h1>Página de Login</h1>
    <form action="" id="formLogin">
        <input type="text" name="" id="login" placeholder="Insira seu login">
        <input type="password" name="" id="senha" placeholder="Insira sua senha">
        <input type="submit" value="Entrar">
    </form>
    <p>Ainda não tem login? <a href="cadastro.php">Cadastre-se aqui</a></p>

    <script src="js/conexao.js"></script>
    <script>
        const formLogin = document.querySelector("#formLogin");
        const login = document.querySelector("#login");
        const senha = document.querySelector("#senha");

        formLogin.addEventListener("submit", async function (e){
            e.preventDefault();
            const conexao = new Conexao("entrar");
            conexao.addData("login", login.value);
            conexao.addData("senha", senha.value);
            resposta = await conexao.tryConnection();
            if(resposta["status"] == "sucesso"){
                alert("Bem-vindo(a)!");
                window.location = 'home.php';
            } else if(resposta["status"] == "falha"){
                alert("Tentativa de login falha!");
            } else if(resposta["status"] == "erro"){
                alert("ERRO: "+ resposta["erro"]);
            } else {
                alert(JSON.stringify(resposta))
            }
        })
    </script>
</body>
</html>