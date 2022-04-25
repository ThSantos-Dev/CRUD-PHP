<?php
/*****************************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos
 *              Obs(Este arquivo fará ponte entre a VIEW e a MODEL)
 * Autor: Thales
 * Data: 04/03/2022
 * Última modificação: 11/03/2022 - implentatação das funções inserirContato,
 *                                      excluirContato, atualizarContato, listaContato
 * Versão: 1.0
 */

//  Função para receber dados da VIEW e encaminhar dados para a MODEL (inserir)
function inserirContato($dadosContato, $file) {
    // Validação para verificar se o objeto está vazio
    if(!empty($dadosContato)){
        // Validação de caixa vazia dos elementos Nome, Celular e Email, pois são obrigatórios no BD
        if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            $fileNames = array();

            if($file != null) {
                require_once('modulo/upload.php');

                echo "<pre>"   ;  
                print_r($file);
                echo "</pre>"   ;  
           
                $total_count = count($_FILES['fileFoto']['name']);
                for($i = 0; $i < $total_count; $i++) {
                    // Recupera o Tamanho do arquivo
                    $sizeFile = $_FILES['fileFoto']['size'][$i];

                    // Recupera o Tipo do arquivo
                    $typeFile = $_FILES['fileFoto']['type'][$i];
            
                    // Recupera o Nome do arquivo
                    $nameFile = $_FILES['fileFoto']['name'][$i];
            
                    // Recupera o Caminho do arquivo temporário
                    $tempFile = $_FILES['fileFoto']['tmp_name'][$i];

                    $arquivo = array(
                        "name" => $nameFile,
                        "type" => $typeFile,
                        "size" => $sizeFile,
                        "tmp_name" => $tempFile
                    );

                    array_push($fileNames,  uploadFile($arquivo));

                }

                
                echo "<pre>"   ;  
                print_r($fileNames);
                echo "</pre>"   ;  
           

                die;

                if(is_array($fileName))
                    return $fileName; 
            }


            /*************************************************************************
             * Criação do array de dados que será encaminhado a model para
             * inserir no BD, é importante criar este array conforme
             * as necessidades de manipulação do BD.
             * 
             * OBS: criar as chaves do Array conforme os nomes dos atributos do BD
             **************************************************************************/

            $arrayDados = array(
                "nome"      => $dadosContato['txtNome'],
                "celular"   => $dadosContato['txtCelular'],
                "telefone"  => $dadosContato['txtTelefone'],
                "email"     => $dadosContato['txtEmail'],
                "obs"       => $dadosContato['txtObs'],
                "file"      => PATH_FILE_UPLOAD . $fileName
            );

            // Import do arquivo de modelagem para manipular o BD
            require_once('models/bd/contato.php');
            // Chama a função que fará o insert no BD (essa função está na Model)
            if(insertContato($arrayDados))
                return true;
            else 
                return array('idErro'   => 1,
                             'message'  => 'Não foi possível inserir os dados no Banco de Dados.');
        }
        else 
            return array('idErro'   => 2,
                         'message'  => 'Erro. Há campos obrigatórios que necessitam ser preenchidos.');   
    }
}

//  Função para realizar a exclusão do contato (excluir)
function excluirContato($id) {
    // Validação para verificar se o $id contém um número VÁLIDO.
    if($id != 0 && !empty($id) && is_numeric($id)){
        // Import do arquivo de modelagem para manipular o BD.
        require_once('models/bd/contato.php');
        
        // Chama a função da models e valida se o retorno foi true ou false
        if(deleteContato($id))
            return true;
        else 
            return array('idErro'   => 3,
                        'message'  => 'O banco de dados não pode excluir o registro.');
    } else {
        return array('idErro'   => 4,
                     'message'  => 'Não é possível excluir um registro sem informar um ID válido.');
    }
}

//  Função para receber dados da VIEW e encaminhar dados para a MODEL (Atualizar)
function atualizarContato($dadosContato, $id) {
    // Validação para verificar se o objeto está vazio
    if(!empty($dadosContato)){
        // Validação de caixa vazia dos elementos Nome, Celular e Email, pois são obrigatórios no BD
        if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            // Validação para garantir que o ID seja válido
            if(!empty($id) && $id != 0 && is_numeric($id)) {
                /*************************************************************************
                 * Criação do array de dados que será encaminhado a model para
                 * inserir no BD, é importante criar este array conforme
                 * as necessidades de manipulação do BD.
                 * 
                 * OBS: criar as chaves do Array conforme os nomes dos atributos do BD
                 **************************************************************************/

                $arrayDados = array(
                    "id"        => $id,
                    "nome"      => $dadosContato['txtNome'],
                    "celular"   => $dadosContato['txtCelular'],
                    "telefone"  => $dadosContato['txtTelefone'],
                    "email"     => $dadosContato['txtEmail'],
                    "obs"       => $dadosContato['txtObs']
                );

                // Import do arquivo de modelagem para manipular o BD
                require_once('models/bd/contato.php');
                // Chama a função que fará o insert no BD (essa função está na Model)
                if(updateContato($arrayDados))
                    return true;
                else 
                    return array('idErro'   => 1,
                                'message'  => 'Não foi possível atualizar os dados no Banco de Dados.');
            }
            else 
                return array('idErro'   => 4,
                            'message'  => 'Não é possível atualizar um registro sem informar um ID válido.');
        }
        else 
            return array('idErro'   => 2,
                         'message'  => 'Erro. Há campos obrigatórios que necessitam ser preenchidos.');   
    }
}

// Função para solicitar dados da model e encaminhar a lista de contatos para a VIEW
function listaContato() {
    // Import do arquivo que vai buscar os dados no BD
    require_once('models/bd/contato.php');

    // Chama a função que vai buscar os dados no BD e guardando o seu conteúdo na variável $dados
    $dados = selectAllContatos();

    if(!empty($dados))
        return $dados;
    else 
        return false;

}

// Função para buscar um contato através do ID do registro
function buscarContato($id) {
        // Validação para verificar se o $id contém um número VÁLIDO.
    if($id != 0 && !empty($id) && is_numeric($id)){
        // Import do arquivo de modelagem para manipular o BD.
        require_once('models/bd/contato.php');
        
        // Chama a função na model que vai buscar no BD 
        $dados = selectByIdContato($id);

        // Valida se existem dados para serem devolvidos
        if(!empty($dados))
            return $dados;
        else
            return false;
    } else 
        return array('idErro'   => 4,
                     'message'  => 'Não é possível buscar um registro sem informar um ID válido.');
}


                    /*************************************************************************** */
                    // LEMBRAR O MARCEL DE CRIAR TABELA COM TODOS OS IDs DE MENSAGEM DE ERRO!!   //
                    /*************************************************************************** */








?>