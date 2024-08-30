<?php
require("../Conexao.php");

$perfil = $_GET["perfil"];
$codigo_usuario = $_GET["codigo_usuario"];

if($perfil === "gestor")
{
    $instrucaoBuscaEnderecoGestor = "select su.rua,su.numero,c.nome,e.nome from system_unit as su inner join cidade as c on su.cidade_id = c.id inner join estado as e on c.estado_id = e.id where su.id = :recebe_codigo_usuario";
    $comandoBuscaEnderecoGestor = Conexao::Obtem()->prepare($instrucaoBuscaEnderecoGestor);
    $comandoBuscaEnderecoGestor->bindValue(":recebe_codigo_usuario",$codigo_usuario);
    $comandoBuscaEnderecoGestor->execute();
    $resultadoBuscaEnderecoGestor = $comandoBuscaEnderecoGestor->fetch(PDO::FETCH_ASSOC);

    if($resultadoBuscaEnderecoGestor)
        echo json_encode($resultadoBuscaEnderecoGestor);
}else{
    $instrucaoBuscaEnderecoFornecedor = "select pe.rua,pe.numero,c.nome,e.nome from system_users as su inner join pessoa as p on p.system_users = su.id inner join pessoa_endereco as pe on pe.pessoa_id = p.id
    inner join cidade as c on pe.cidade_id = c.id inner join estado as e on c.estado_id = e.id where su.id = :recebe_codigo_usuario";
    $comandoBuscaEnderecoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaEnderecoFornecedor);
    $comandoBuscaEnderecoFornecedor->bindValue(":recebe_codigo_usuario",$codigo_usuario);
    $comandoBuscaEnderecoFornecedor->execute();
    $resultadoBuscaEnderecoFornecedor = $comandoBuscaEnderecoFornecedor->fetch(PDO::FETCH_ASSOC);

    if($resultadoBuscaEnderecoFornecedor)
        echo json_encode($resultadoBuscaEnderecoFornecedor);
}
?>