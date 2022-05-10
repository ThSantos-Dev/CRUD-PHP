<?php
/*****************************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de estadis
 *              Obs(Este arquivo fará ponte entre a VIEW e a MODEL)
 * Autor: Thales
 * Data: 10/05/2022
 * Versão: 1.0
 ****************************************************************************/

 // Import do arquivo de configuração do projeto
require_once('modulo/config.php');

function listaEstado() {
    // Import do arquivo que vai buscar os dados no BD
    require_once('models/bd/estado.php');

    // Chama a função que vai buscar os dados no BD e guardando o seu conteúdo na variável $dados
    $dados = selectAllEstados();

    if(!empty($dados))
        return $dados;
    else 
        return false;

}

function buscarEstado($id) {
    return selectByIdEstado($id);
}




?>