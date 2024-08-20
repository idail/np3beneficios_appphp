<?php
require("../Conexao.php");

$recebeUsuario = $_GET["usuario"];
$recebeSenha = $_GET["senha"];

$recebeSenhaCripografada = md5($recebeSenha);

$instrucaoBuscaSystemID = "select * from system_users where login = :recebe_login and password = :recebe_senha";
$comandoBuscaUsuario = Conexao::Obtem()->prepare($instrucaoBuscaSystemID);
$comandoBuscaUsuario->bindValue(":recebe_login",$recebeUsuario);
$comandoBuscaUsuario->bindValue(":recebe_senha",$recebeSenhaCripografada);
$comandoBuscaUsuario->execute();
$resultado = $comandoBuscaUsuario->fetch(PDO::FETCH_ASSOC);

$id_systemusers = $resultado["id"];

$instrucaoBuscaIDPessoa = "select id from pessoa where system_user_id = :recebe_system_id";
$comandoBuscaIDPessoa = Conexao::Obtem()->prepare($instrucaoBuscaIDPessoa);
$comandoBuscaIDPessoa->bindValue(":recebe_system_id",$id_systemusers);
$comandoBuscaIDPessoa->execute();
$resultado_pessoa = $comandoBuscaIDPessoa->fetch(PDO::FETCH_ASSOC);

$id_pessoa = $resultado_pessoa["id"];

$instrucaoBuscaIDPessoaGrupo = "select pessoa_id,grupo_pessoa_id from pessoa_grupo where pessoa_id = :recebe_id_pessoa";
$comandoBuscaIDPessoaGrupo = Conexao::Obtem()->prepare($instrucaoBuscaIDPessoaGrupo);
$comandoBuscaIDPessoaGrupo->bindValue(":recebe_id_pessoa",$id_pessoa);
$comandoBuscaIDPessoaGrupo->execute();
$resultado_pessoa_grupo = $comandoBuscaIDPessoaGrupo->fetch(PDO::FETCH_ASSOC);

$grupo_pessoa = $resultado_pessoa_grupo["grupo_pessoa_id"];

$instrucaoBuscaNomeGrupoPessoa = "select nome from grupo_pessoa where id = :recebe_id_grupo_pessoa";
$comandoBuscaNomeGrupoPessoa = Conexao::Obtem()->prepare($instrucaoBuscaNomeGrupoPessoa);
$comandoBuscaNomeGrupoPessoa->bindValue(":recebe_id_grupo_pessoa",$grupo_pessoa);
$comandoBuscaNomeGrupoPessoa->execute();
$resultado_nome_grupo_pessoa = $comandoBuscaNomeGrupoPessoa->fetch(PDO::FETCH_ASSOC);

//$recebe_grupo_pessoa = $resultado_nome_grupo_pessoa["nome"];

echo json_encode($resultado_nome_grupo_pessoa);
?>