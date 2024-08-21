<?php
require("../Conexao.php");
$codigo = $_GET["codigo_usuario"];
$instrucaoBuscaPedidos = "select * from pedido as p where p.system_users_id = :recebe_codigo_usuario GROUP BY descricaopedido";
$comandoBuscaPedidos = Conexao::Obtem()->prepare($instrucaoBuscaPedidos);
$comandoBuscaPedidos->bindValue(":recebe_codigo_usuario",$codigo);
$comandoBuscaPedidos->execute();
$resultado = $comandoBuscaPedidos->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>