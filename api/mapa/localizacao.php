<?php
require("../Conexao.php");

$perfil = $_GET["perfil"];
$codigo_usuario = $_GET["codigo_usuario"];

if($perfil === "gestor")
{
    $instrucaoBuscaEnderecoGestor = "select sunit.rua,sunit.numero,c.nome,e.nome from system_unit as sunit inner join 
    system_users as su on sunit.id = su.system_unit_id inner join cidade as c on sunit.cidade_id = c.id
     inner join estado as e on c.estado_id = e.id where su.id = :recebe_codigo_usuario";
    $comandoBuscaEnderecoGestor = Conexao::Obtem()->prepare($instrucaoBuscaEnderecoGestor);
    $comandoBuscaEnderecoGestor->bindValue(":recebe_codigo_usuario",$codigo_usuario);
    $comandoBuscaEnderecoGestor->execute();
    $resultadoBuscaEnderecoGestor = $comandoBuscaEnderecoGestor->fetch(PDO::FETCH_ASSOC);

    if($resultadoBuscaEnderecoGestor)
        echo json_encode($resultadoBuscaEnderecoGestor);
}else{
    $instrucaoBuscaEnderecoFornecedor = "select pend.rua,pend.numero,c.nome,e.nome from system_users as su inner join
     pessoa as pe on su.id = pe.system_user_id inner join pessoa_endereco as pend on pe.id = pend.pessoa_id inner join
      cidade as c on pend.cidade_id = c.id inner join estado as e on c.estado_id = e.id where su.id = :recebe_codigo_usuario";
    $comandoBuscaEnderecoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaEnderecoFornecedor);
    $comandoBuscaEnderecoFornecedor->bindValue(":recebe_codigo_usuario",$codigo_usuario);
    $comandoBuscaEnderecoFornecedor->execute();
    $resultadoBuscaEnderecoFornecedor = $comandoBuscaEnderecoFornecedor->fetch(PDO::FETCH_ASSOC);

    if($resultadoBuscaEnderecoFornecedor)
        echo json_encode($resultadoBuscaEnderecoFornecedor);
}
?>