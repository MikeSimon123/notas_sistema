class Conexao{
    constructor(comando){
        this.dados = {"comando":`${comando}`, "dados":{}};
    }
    addData(chave, dado){
        this.dados["dados"][`${chave}`] = dado;
    }
    async tryConnection(){
        const resposta = await fetch("../php/acoes.php", {
            method: "post",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(this.dados)
        })
        const dados = await resposta.json();
        return dados;
    }
}