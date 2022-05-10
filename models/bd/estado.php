<?php
/**
 * Objetivo: Arquivo responsável por manipular os dados dentro do BD
 *                  (select)
 * Autor: Thales Santos
 * Data: 10/05/2022
 * */

require_once('conexaoMySQL.php');

// Função para realizar o LISTAR TODOS OS ESTADOS do BD
function selectAllEstados()
{
    $arrayDados = array();
    // Abre a conexão com o BD
    $conexao = conexaoMySQL();

    // script para listar todos os estados do BD
    $sql = "select * from tblEstados order by nome asc";
    $result = mysqli_query($conexao, $sql);

    // Valida se o BD retornou registros
    if ($result) {
        // dentro do while o $rsDados recebe os dados do banco, converte e ainda conta quantos dados tem
        // assim que chega no ultimo elemento do array, automaticamente o loop cessa

        /**
         * mysqli_fetch_assoc() - permite converter os dados do BD em um array para 
         *                          manipulação no PHP 
         * Nesta repetição estamos, convertendo os dados do BD em um array ($rsDados),
         * além de o próprio while conseguir gerenciar a quantidade vezes que deverá ser feita
         * a repetição
         * 
         * vai gerenciar a quantidade de itens no array
         */
        $cont = 0;
        while ($rsDados = mysqli_fetch_assoc($result)) {
            // Cria um array com dados do BD
            $arrayDados[$cont] = array(
                "id"        => $rsDados['idEstado'],
                "nome"      => $rsDados['nome'],
                "sigla"     => $rsDados['sigla']
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o BD
        fecharConexaoMySQL($conexao);

        return $arrayDados;
    }
}

function selectByIdEstado($id) {

    $conexao = conexaoMySQL();

    $sql = "select * from tblEstados where idEstado=". $id;

    $dados = mysqli_query($conexao, $sql);

    if($estado = mysqli_fetch_assoc($dados)) {
        $arrayDados = array(
            "idEstado" => $estado['idEstado'],
            "nome"     => $estado['nome'],
            "sigla"    => $estado['sigla']
        );

        return $arrayDados;
    }
}















?>