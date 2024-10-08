<?php
require("../Conexao.php");

$recebeCodigoUsuario = $_GET["codigo_usuario"];
$recebePerfilUsuario = $_GET["perfil"];
$recebeTipoBusca = $_GET["tipo_busca"];

if($recebePerfilUsuario === "gestor" && $recebeCodigoUsuario != "")
{
    if($recebeTipoBusca === "valor_empenho")
    {
        $instrucaoBuscaValoresGestor = "select valor_empenho from departamento_unit as du inner join system_user_departamento_unit as sud
        on sud.departamento_unit_id = du.id where sud.system_users_id = :recebe_codigo_usuario";
        $comandoBuscaValoresGestor = Conexao::Obtem()->prepare($instrucaoBuscaValoresGestor);
        $comandoBuscaValoresGestor->bindValue(":recebe_codigo_usuario",$recebeCodigoUsuario);
        $comandoBuscaValoresGestor->execute();
        $resultadoGestor = $comandoBuscaValoresGestor->fetchAll(PDO::FETCH_ASSOC);

        if($resultadoGestor)
            echo json_encode($resultadoGestor);
    }else{
        $instrucaoBuscaValorContacaoGestor = "SELECT SUM(valor_total_cotacao)
        FROM pedido 
        WHERE system_users_id = :recebe_codigo_usuario 
        AND estado_pedido_venda_id IN (13, 8);";
        $comandoBuscaValorContacaoGestor = Conexao::Obtem()->prepare($instrucaoBuscaValorContacaoGestor);
        $comandoBuscaValorContacaoGestor->bindValue(":recebe_codigo_usuario",$recebeCodigoUsuario);
        $comandoBuscaValorContacaoGestor->execute();
        $resultadoValorCotacao = $comandoBuscaValorContacaoGestor->fetchAll(PDO::FETCH_ASSOC);

        if($resultadoValorCotacao)
            echo json_encode($resultadoValorCotacao);
    }
    $instrucaoBuscaValorCotacaoGestor = "select sum(valor_total_cotacao) from pedido as p 
    where p.system_users_id = recebe_codigo_usuario and ";
}else if($recebePerfilUsuario === "fornecedor" && $recebeCodigoUsuario != "")
{
    if($recebeTipoBusca === "valor_cotacao_pago")
    {
        $instrucaoBuscaValorPago = "select sum(valor_total_cotacao) from pedido as p where p.cliente_id = :recebe_codigo_usuario AND estado_pedido_venda_id IN (13, 8)";
        $comandoBuscarValorPago = Conexao::Obtem()->prepare($instrucaoBuscaValorPago);
        $comandoBuscarValorPago->bindValue(":recebe_codigo_usuario",$recebeCodigoUsuario);
        $comandoBuscarValorPago->execute();
        $resultadoValorPago = $comandoBuscarValorPago->fetchAll(PDO::FETCH_ASSOC);

        if($resultadoValorPago)
            echo json_encode($resultadoValorPago);
    }else{
        $instrucaoBuscaValorAberto = "select sum(valor_total_cotacao) from pedido as p where p.cliente_id = :recebe_codigo_usuario AND estado_pedido_venda_id IN (13, 8, 11)";
        $comandoBuscarValorAberto = Conexao::Obtem()->prepare($instrucaoBuscaValorAberto);
        $comandoBuscarValorAberto->bindValue(":recebe_codigo_usuario",$recebeCodigoUsuario);
        $comandoBuscarValorAberto->execute();
        $resultadoValorAberto = $comandoBuscarValorAberto->fetchAll(PDO::FETCH_ASSOC);

        if($resultadoValorAberto)
            echo json_encode($resultadoValorAberto);
    }
}
?>