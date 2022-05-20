<?php
/**************************************************************************************
 * Objetivo: Arquivo responsável pela criação de constante e variáveis do projeto
 * Autor: Thales Santos
 * Data: 25/04/2022
 * Versão: 1.0
 *************************************************************************************/

 /***************************             VARIÁVEIS E CONSTANTES GLOBAIS DO PROJETO              *********************************** */
    //  Limitação de 5MB para upload de imagens
    const MAX_SIZE_FILE_UPLOAD = 5120;

    // Extensões de arquivos permitiadas para upload de imagens
    const EXT_ALLOWED_FILE_UPLOAD = array("image/jpg", "image/png", "image/jpeg", "image/gif");

    // Diretório onde os arquivos ficarão salvos
    const PATH_FILE_UPLOAD = 'uploads/';

    define('SRC', $_SERVER['DOCUMENT_ROOT'] . '/thales/AULA07/');


 /***************************             FUNÇÕES GLOBAIS DO PROJETO              *********************************** */

 
 /**
 * Função para  converter um Array em um formato JSON
 * @author Thales Santos
 * @param Array $arrayDados Array com os dados que serão transformados em JSON
 * @return JSON Contém os dados do array convertidos para JSON
 */
function createJSON($arrayDados){
    // Validação para tratar array sem dados
    if(!empty($arrayDados)) {
        // Configura o padrão da conversão em um formato JSON
        header('Content-Type: application/json');
    
        $dadosJSON = json_encode($arrayDados);
    
        return $dadosJSON;
    } else 
        return false;
}


?>