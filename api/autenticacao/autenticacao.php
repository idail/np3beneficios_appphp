<?php
require("../Conexao.php");

$recebeUsuario = $_GET["usuario"];
$recebeSenha = $_GET["senha"];

$recebeSenhaCripografada = md5($recebeSenha);

$id = "";
$id_grupo_pessoafornecedor = "";
$id_departamentofornecedor = "";
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
    $login_usuario = $resultado_fornecedor["login"];

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

    $instrucaoBuscaCodigoDepartamentoFornecedor = "select departamento_unit_id from pessoa_departamento where pessoa_id = :recebe_codigo_usuario";
    $comandoBuscaCodigoDepartamentoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaCodigoDepartamentoFornecedor);
    $comandoBuscaCodigoDepartamentoFornecedor->bindValue(":recebe_codigo_usuario",$id);
    $comandoBuscaCodigoDepartamentoFornecedor->execute();
    $resultadoBuscaCodigoDepartamentoFornecedor = $comandoBuscaCodigoDepartamentoFornecedor->fetch(PDO::FETCH_ASSOC);

    if($resultadoBuscaCodigoDepartamentoFornecedor)
        $id_departamentofornecedor = $resultadoBuscaCodigoDepartamentoFornecedor["departamento_unit_id"];
    else
        $id_departamentofornecedor = "nao localizado";

    $informacoes = ["nome" => $nome_usuario,"nome_grupo_usuario" => $nome_grupo, "codigo_usuario_autenticado" => $id, 
    "codigo_departamento_fornecedor" => $id_departamentofornecedor, "login_usuario" => $login_usuario];
}else{
    $instrucaoBuscaGestor = "SELECT * FROM system_users where id not in(select system_user_id from pessoa) and login = :recebe_login_usuario and password = :recebe_senha_usuario";
    $comandoBuscaGestor = Conexao::Obtem()->prepare($instrucaoBuscaGestor);
    $comandoBuscaGestor->bindValue(":recebe_login_usuario",$recebeUsuario);
    $comandoBuscaGestor->bindValue(":recebe_senha_usuario",$recebeSenhaCripografada);
    $comandoBuscaGestor->execute();
    $resultado_gestor = $comandoBuscaGestor->fetch(PDO::FETCH_ASSOC);

    if($resultado_gestor)
    {
        $id = $resultado_gestor["id"];
        $nome_usuario = $resultado_gestor["name"];
        $login_usuario = $resultado_gestor["login"];
        $email_usuario = $resultado_gestor["email"];
        
        $instrucaoSql = "select * from system_user_departamento_unit where system_users_id = :id";
        $comandoBuscaGestor = Conexao::Obtem()->prepare($instrucaoSql);
        $comandoBuscaGestor->bindValue(":id",$id);
        $comandoBuscaGestor->execute();
        $resultado_departamentos_gestor = $comandoBuscaGestor->fetchAll(PDO::FETCH_ASSOC);

        $informacoes = ["nome" => $nome_usuario,"nome_grupo_usuario" => "Gestor", "codigo_usuario_autenticado" => $id, "login_usuario" => $login_usuario, "email_usuario" => $email_usuario, 
        "departamentos" => $resultado_departamentos_gestor];

        echo json_encode($informacoes);
    }else{
        echo json_encode("nenhum usuario localizado");
    }
}

// if(!empty($informacoes))
// echo json_encode($informacoes);
?>