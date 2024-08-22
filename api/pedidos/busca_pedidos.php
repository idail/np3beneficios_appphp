<?php
require("../Conexao.php");
$codigo = $_GET["codigo_usuario"];
$tipo_acesso = $_GET["tipo_acesso"];

if($tipo_acesso === "Gestor")
{
    $instrucaoBuscaPedidosGestor = "SELECT DISTINCT p.id,p.dt_pedido,p.descricaopedido,ep.nome,p.valor_total,p.valor_total_cotacao FROM system_user_departamento_unit as su inner join
     pedido as p on su.departamento_unit_id = p.departamento_unit_id inner join estado_pedido as ep on p.estado_pedido_venda_id = ep.id where p.system_users_id = :recebe_codigo_usuario and ep.id = 13;";
    $comandoBuscaPedidosGestor = Conexao::Obtem()->prepare($instrucaoBuscaPedidosGestor);
    $comandoBuscaPedidosGestor->bindValue(":recebe_codigo_usuario",$codigo);
    $comandoBuscaPedidosGestor->execute();
    $resultado = $comandoBuscaPedidosGestor->fetchAll(PDO::FETCH_ASSOC);
}
echo json_encode($resultado);
?>