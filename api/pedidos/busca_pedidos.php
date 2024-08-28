<?php
require("../Conexao.php");
$codigo = $_GET["codigo_usuario"];
$tipo_acesso = $_GET["tipo_acesso"];

if(isset($_GET["codigo_fornecedor_departamento"]))
    $codigo_departamento_fornecedor = $_GET["codigo_fornecedor_departamento"];

/*SELECT DISTINCT p.id,p.dt_pedido,p.descricaopedido,ep.nome,p.valor_total,p.valor_total_cotacao FROM system_user_departamento_unit as 
    su inner join pedido as p on su.departamento_unit_id = p.departamento_unit_id inner join 
    estado_pedido as ep on p.estado_pedido_venda_id = ep.id where p.system_users_id = :recebe_codigo_usuario and ep.id = 13*/

// if($tipo_acesso === "gestor")
// {
//     $instrucaoBuscaPedidosGestor = "
    

//     SELECT DISTINCT p.id, p.dt_pedido, p.descricaopedido, ep.nome AS estado_pedido, p.valor_total, p.valor_total_cotacao FROM system_user_departamento_unit su INNER JOIN pedido p ON
//     su.departamento_unit_id = p.departamento_unit_id INNER JOIN estado_pedido ep ON p.estado_pedido_venda_id = ep.id WHERE p.system_users_id = :recebe_codigo_usuario AND ep.id = 13 ORDER BY `p`.`id` ASC; 
    
    
//     ";
//     $comandoBuscaPedidosGestor = Conexao::Obtem()->prepare($instrucaoBuscaPedidosGestor);
//     $comandoBuscaPedidosGestor->bindValue(":recebe_codigo_usuario",$codigo);
//     $comandoBuscaPedidosGestor->execute();
//     $resultado = $comandoBuscaPedidosGestor->fetchAll(PDO::FETCH_ASSOC);
// }else{
//     $instrucaoBuscaPedidoFornecedor = "SELECT DISTINCT pe.id,pe.dt_pedido,pe.descricaopedido,ep.nome,pe.valor_total,pe.valor_total_cotacao
//     FROM system_users su
//     INNER JOIN pessoa p ON su.id = p.system_user_id
//     INNER JOIN pessoa_departamento pd ON su.id = pd.pessoa_id
//     INNER JOIN pedido pe ON pe.departamento_unit_id = 23 AND pe.cliente_id = 21
//     INNER JOIN estado_pedido ep ON ep.id = pe.estado_pedido_venda_id
//     WHERE ep.id IN (13, 18, 17, 8)";
//     $comandoBuscaPedidoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaPedidoFornecedor);
//     // $comandoBuscaPedidoFornecedor->bindValue(":recebe_codigo_departamento",23);
//     // $comandoBuscaPedidoFornecedor->bindValue(":recebe_codigo_usuario",21);
//     $comandoBuscaPedidoFornecedor->execute();
//     $resultado = $comandoBuscaPedidoFornecedor->fetchAll(PDO::FETCH_ASSOC);
// }

if($tipo_acesso === "gestor")
{
    /*$instrucaoBuscaPedidosGestor = "SELECT DISTINCT p.id, p.dt_pedido, p.descricaopedido, ep.nome AS estado_pedido, p.valor_total, p.valor_total_cotacao 
    FROM system_user_departamento_unit su INNER JOIN pedido p ON
    su.departamento_unit_id = p.departamento_unit_id INNER JOIN estado_pedido ep ON p.estado_pedido_venda_id = ep.id WHERE p.system_users_id = :recebe_codigo_usuario 
    AND ep.id = 13 ORDER BY `p`.`id` ASC";*/

    $instrucaoBuscaPedidosGestor = "SELECT * FROM ( SELECT p.id, pe.nome AS Fornecedor, p.dt_pedido, p.descricaopedido, ep.nome AS estado_pedido, p.valor_total, p.valor_total_cotacao, ROW_NUMBER() 
    OVER (PARTITION BY p.id ORDER BY p.dt_pedido DESC) as rn FROM pedido p INNER JOIN system_user_departamento_unit su ON su.departamento_unit_id = p.departamento_unit_id INNER JOIN estado_pedido ep 
    ON p.estado_pedido_venda_id = ep.id INNER JOIN pessoa_departamento pd ON pd.departamento_unit_id = p.departamento_unit_id INNER JOIN pessoa pe ON pd.pessoa_id = pe.id 
    WHERE p.system_users_id = :recebe_codigo_usuario 
    AND ep.id = 13 ) as subquery WHERE rn = 1;";
    $comandoBuscaPedidosGestor = Conexao::Obtem()->prepare($instrucaoBuscaPedidosGestor);
    $comandoBuscaPedidosGestor->bindValue(":recebe_codigo_usuario",$codigo);
    $comandoBuscaPedidosGestor->execute();
    $resultado_gestor = $comandoBuscaPedidosGestor->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultado_gestor);
}else{
    $instrucaoBuscaPedidoFornecedor = "SELECT DISTINCT pe.id,pe.dt_pedido,pe.descricaopedido,ep.nome,pe.valor_total,pe.valor_total_cotacao
    FROM system_users su
    INNER JOIN pessoa p ON su.id = p.system_user_id
    INNER JOIN pessoa_departamento pd ON su.id = pd.pessoa_id
    INNER JOIN pedido pe ON pe.departamento_unit_id = :recebe_codigo_departamento AND pe.cliente_id = :recebe_codigo_usuario
    INNER JOIN estado_pedido ep ON ep.id = pe.estado_pedido_venda_id
    WHERE ep.id IN (13, 18, 17, 8)";
    $comandoBuscaPedidoFornecedor = Conexao::Obtem()->prepare($instrucaoBuscaPedidoFornecedor);
    $comandoBuscaPedidoFornecedor->bindValue(":recebe_codigo_departamento",$codigo_departamento_fornecedor);
    $comandoBuscaPedidoFornecedor->bindValue(":recebe_codigo_usuario",$codigo);
    $comandoBuscaPedidoFornecedor->execute();
    $resultado_fornecedor = $comandoBuscaPedidoFornecedor->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultado_fornecedor);
}
?>