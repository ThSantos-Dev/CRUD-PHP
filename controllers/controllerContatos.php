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

// Import do arquivo de configuração do projeto
require_once(SRC.'modulo/config.php');

//  Função para receber dados da VIEW e encaminhar dados para a MODEL (inserir)
function inserirContato($dadosContato) {
    $fileName = (string) 'img/user.png';

    // Validação para verificar se o objeto está vazio
    if(!empty($dadosContato)){
        // Recebe o objeto imagem que foi encaminhado dentro do Array
        $file = $dadosContato['file'];

        // Validação de caixa vazia dos elementos Nome, Celular e Email e Estado, pois são obrigatórios no BD
        if(!empty($dadosContato[0]['nome']) && !empty($dadosContato[0]['celular']) && !empty($dadosContato[0]['email']) && !empty($dadosContato[0]['estado'])){
            // Validação para identificar se chegou um arquivo para upload
            if($file['foto']['name'] != null) {
                // Import da função de upload
                require_once(SRC . 'modulo/upload.php');

                // Chama a função de upload
                $fileName = uploadFile($file['foto']);

                if(is_array($fileName))
                // Caso ocorra erros no processo de upload, a função irá retornar um array com a possível mensagem de erro.
                // Esse array será retornado para a Router que irá exibir na tela
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
                "nome"      => $dadosContato[0]['nome'],
                "celular"   => $dadosContato[0]['celular'],
                "telefone"  => $dadosContato[0]['telefone'],
                "email"     => $dadosContato[0]['email'],
                "obs"       => $dadosContato[0]['obs'],
                "foto"      => $fileName,
                "idEstado"  => $dadosContato[0]['estado']
            );

            // Import do arquivo de modelagem para manipular o BD
            require_once(SRC . 'models/bd/contato.php');
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
function excluirContato($arrayDados) {
    // Recebe o ID do registro que será excluído
    $id = $arrayDados['id'];

    // Recebe o nome da foto que será excluída da pasta do servidor
    $idFoto = isset($arrayDados['idFoto']) ? $arrayDados['idFoto'] : null;

    // Validação para verificar se o $id contém um número VÁLIDO.
    if($id != 0 && !empty($id) && is_numeric($id)){
        // Import do arquivo de modelagem para manipular o BD.
        require_once(SRC . 'models/bd/contato.php');
        // require_once('modulo/config.php');

        // Chama a função da models e valida se o retorno foi true ou false
        if(deleteContato($id)){
            // Validação que verifica se a variável Foto tem conteúdo
            if($idFoto != null) {
                // unlink() - função para apagar um arquivo de um diretório
                // Permimte apagar a foto fisicamente do diretório do servidor
                if(file_exists(SRC . PATH_FILE_UPLOAD . $idFoto)){
                    if(unlink(SRC . PATH_FILE_UPLOAD . $idFoto))
                        return true;
                }
                else
                    return array('idErro'   => 5,
                                'message'  => 'O contato foi excluido do banco de dados. Porém a imagem não pode ser excluída.');
            } else 
                return true;
        }
        else 
            return array('idErro'   => 3,
                        'message'  => 'O banco de dados não pode excluir o registro.');
    } else {
        return array('idErro'   => 4,
                     'message'  => 'Não é possível excluir um registro sem informar um ID válido.');
    }
}

//  Função para receber dados da VIEW e encaminhar dados para a MODEL (Atualizar)
function atualizarContato($dadosContato, $arrayDados) {
    // Recebe o ID enviado pelo $arrayDados
    $id     = $arrayDados['id'];

    // Recebe o nome da foto enviada pelo arraydados (nome da foto que já existe no BD)
    $idFoto = $arrayDados['idFoto'];

    // Recebe o objeto de array referente a nova foto que poderá ser enviada no servidor
    $file   = $arrayDados['file']  ;

    // Variável para sabermos se é para apagar a imagem do servidor ou não
    $newUpload = (bool) false;
    

    // Validação para verificar se o objeto está vazio
    if(!empty($dadosContato)){
        // Validação de caixa vazia dos elementos Nome, Celular e Email, pois são obrigatórios no BD
        if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            // Validação para garantir que o ID seja válido
            if(!empty($id) && $id != 0 && is_numeric($id)) {
                // Validação para identificar se será enviado ao servidor uma nova FOTO
                if($file['fileFoto']['name'] != null) {
                    // Atualizando a variável, indicando que há um novo upload, ou seja
                    // a imagem antiga deve ser apagada da pasta do servidor
                    $newUpload = true;

                    // Import da função de upload
                    require_once('modulo/upload.php');
    
                    // Chama a função de upload
                    $newFileName = uploadFile($file['fileFoto']);
    
                    if(is_array($newFileName))
                        // Caso ocorra erros no processo de upload, a função irá retornar um array com a possível mensagem de erro.
                        // Esse array será retornado para a Router que irá exibir na tela
                        return $newFileName;
                } else 
                    // Permanece a mesma foto no banco de dados
                    $newFileName = $idFoto;
                
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
                    "obs"       => $dadosContato['txtObs'],
                    "foto"      => $newFileName,
                    "idEstado"  => $dadosContato['sltEstado']
                );

                // Import do arquivo de modelagem para manipular o BD
                require_once('models/bd/contato.php');

                // Chama a função que fará o insert no BD (essa função está na Model)
                if(updateContato($arrayDados)){
                    // Verificando se será necessário apagar a foto antiga
                    // Esta variável foi ativada em 'true' na linha 130, quando realizamos o upload de uma nova foto para o servidor
                   if($newUpload)
                        // Apaga a foto antiga da pasta do servidor
                       unlink(PATH_FILE_UPLOAD . $newFileName );
                    return true;
                }
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
    require_once(SRC.'models/bd/contato.php');
 
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
        require_once(SRC . 'models/bd/contato.php');
        
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