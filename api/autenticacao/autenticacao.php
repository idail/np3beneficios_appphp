<?php
require("../Conexao.php");

$recebeUsuario = $_GET["usuario"];
$recebeSenha = $_GET["senha"];

$recebeSenhaCripografada = md5($recebeSenha);

$id_fornecedor = "";
$id_grupo_pessoafornecedor = "";
$nome_grupo = "";

$instrucaoBuscaFornecedor = "SELECT * FROM system_users where id in(select system_user_id from pessoa) and login = :recebe_login_usuario and password = :recebe_senha_usuario";
$comandoBuscaFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaFornecedor);
$comandoBuscaFornecedor->bindValue(":recebe_login_usuario",$recebeUsuario);
$comandoBuscaFornecedor->bindValue(":recebe_senha_usuario",$recebeSenhaCripografada);
$comandoBuscaFornecedor->execute();
$resultado_fornecedor = $comandoBuscaFornecedor->fetch(PDO::FETCH_ASSOC);

if($resultado_fornecedor)
    $id_fornecedor = $resultado_fornecedor["id"];
else
    $id_fornecedor = "nao localizado";


$instrucaoBuscaGPFornecedor = "select grupo_pessoa_id from pessoa_grupo where pessoa_id = :recebe_pessoa_id";
$comandoBuscaGPFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaGPFornecedor);
$comandoBuscaGPFornecedor->bindValue(":recebe_pessoa_id",$id_fornecedor);
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

// $instrucaoBuscaSystemID = "select * from system_users where login = :recebe_login and password = :recebe_senha";
// $comandoBuscaUsuario = Conexao::Obtem()->prepare($instrucaoBuscaSystemID);
// $comandoBuscaUsuario->bindValue(":recebe_login",$recebeUsuario);
// $comandoBuscaUsuario->bindValue(":recebe_senha",$recebeSenhaCripografada);
// $comandoBuscaUsuario->execute();
// $resultado = $comandoBuscaUsuario->fetch(PDO::FETCH_ASSOC);

// $id_systemusers = $resultado["id"];

// $instrucaoBuscaIDPessoa = "select id from pessoa where system_user_id = :recebe_system_id";
// $comandoBuscaIDPessoa = Conexao::Obtem()->prepare($instrucaoBuscaIDPessoa);
// $comandoBuscaIDPessoa->bindValue(":recebe_system_id",$id_systemusers);
// $comandoBuscaIDPessoa->execute();
// $resultado_pessoa = $comandoBuscaIDPessoa->fetch(PDO::FETCH_ASSOC);

// $id_pessoa = $resultado_pessoa["id"];

// $instrucaoBuscaIDPessoaGrupo = "select pessoa_id,grupo_pessoa_id from pessoa_grupo where pessoa_id = :recebe_id_pessoa";
// $comandoBuscaIDPessoaGrupo = Conexao::Obtem()->prepare($instrucaoBuscaIDPessoaGrupo);
// $comandoBuscaIDPessoaGrupo->bindValue(":recebe_id_pessoa",$id_pessoa);
// $comandoBuscaIDPessoaGrupo->execute();
// $resultado_pessoa_grupo = $comandoBuscaIDPessoaGrupo->fetch(PDO::FETCH_ASSOC);

// $grupo_pessoa = $resultado_pessoa_grupo["grupo_pessoa_id"];

// $instrucaoBuscaNomeGrupoPessoa = "select nome from grupo_pessoa where id = :recebe_id_grupo_pessoa";
// $comandoBuscaNomeGrupoPessoa = Conexao::Obtem()->prepare($instrucaoBuscaNomeGrupoPessoa);
// $comandoBuscaNomeGrupoPessoa->bindValue(":recebe_id_grupo_pessoa",$grupo_pessoa);
// $comandoBuscaNomeGrupoPessoa->execute();
// $resultado_nome_grupo_pessoa = $comandoBuscaNomeGrupoPessoa->fetch(PDO::FETCH_ASSOC);

//$recebe_grupo_pessoa = $resultado_nome_grupo_pessoa["nome"];

echo json_encode($nome_grupo);
?>