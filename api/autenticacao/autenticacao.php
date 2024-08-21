<?php
require("../Conexao.php");

$recebeUsuario = $_GET["usuario"];
$recebeSenha = $_GET["senha"];

$recebeSenhaCripografada = md5($recebeSenha);

$id = "";
$id_grupo_pessoafornecedor = "";
$nome_grupo = "";
$nome_usuario = "";

$instrucaoBuscaFornecedor = "SELECT * FROM system_users where id in(select system_user_id from pessoa) and login = :recebe_login_usuario and password = :recebe_senha_usuario";
$comandoBuscaFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaFornecedor);
$comandoBuscaFornecedor->bindValue(":recebe_login_usuario",$recebeUsuario);
$comandoBuscaFornecedor->bindValue(":recebe_senha_usuario",$recebeSenhaCripografada);
$comandoBuscaFornecedor->execute();
$resultado_fornecedor = $comandoBuscaFornecedor->fetch(PDO::FETCH_ASSOC);

if($resultado_fornecedor)
{
    $id = $resultado_fornecedor["id"];
    $nome_usuario = $resultado_fornecedor["name"];
}
else{
    $id = "nao localizado";
}
    


$instrucaoBuscaGPFornecedor = "select grupo_pessoa_id from pessoa_grupo where pessoa_id = :recebe_pessoa_id";
$comandoBuscaGPFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaGPFornecedor);
$comandoBuscaGPFornecedor->bindValue(":recebe_pessoa_id",$id);
$comandoBuscaGPFornecedor->execute();
$resultado_gpfornecedor = $comandoBuscaGPFornecedor->fetch(PDO::FETCH_ASSOC);

if($resultado_gpfornecedor)
    $id_grupo_pessoafornecedor = $resultado_gpfornecedor["grupo_pessoa_id"];
else
    $id_grupo_pessoafornecedor = "nao localizado";

$instrucaoBuscaGrupoFornecedor = "select nome from grupo_pessoa where id = :recebe_id_grupo_pessoa";
$comandoBuscaGrupoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaGrupoFornecedor);
$comandoBuscaGrupoFornecedor->bindValue(":recebe_id_grupo_pessoa",$id_grupo_pessoafornecedor);
$comandoBuscaGrupoFornecedor->execute();
$resultadoBuscaGrupoFornecedor = $comandoBuscaGrupoFornecedor->fetch(PDO::FETCH_ASSOC);

if($resultadoBuscaGrupoFornecedor)
    $nome_grupo = $resultadoBuscaGrupoFornecedor["nome"];
else
    $nome_grupo = "nao localizado";

$informacoes = ["nome" => $nome_usuario,"nome_grupo_usuario" => $nome_grupo, "codigo_usuario_autenticado" => $id];


echo json_encode($informacoes);
?>