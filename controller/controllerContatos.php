<!-- Objetivo: arquivo responsável pela manipulação de dados de contatos. Este arquivo fará a ponte entre a view e a model; 
    autora: Carolina Silva; data de criação: 04/03/2022; última modificação: 01/04/2022; versão: 1.0 
-->

<?php
    //função para receber os dados da view e encaminhar para a model (inserir)
    function inserirContato($dadosContato, $file) {
        /* declarando variável e iniciando como nula para o caso de não haver upload */
        $nomeFoto = (string) null;

        //verificando se o objeto $dadosContato não está vazio
        if (!empty($dadosContato)) {
            //validando se as caixas de texto de nome e celular não estão vazias, pois o preenchimento é obrigatório no banco de dados
            if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {

                /* validando se chegou algum arquivo para upload */
                if ($file != null) {
                    /* import do arquivo que contém a função de upload */
                    require_once('modulo/upload.php');

                    /* chamando a função de upload */
                    $nomeFoto = uploadFile($file['fleFoto']);

                    /* verificando se tudo ocorreu como esperado ou não */
                    if (is_array($nomeFoto)) {
                        /* no caso de erros no processo de upload, a função retornará um array com a mensagem de erro. tal array será retornado para a router e ela exibirá a mensagem para o usuário */
                        return $nomeFoto;
                    } 
                                      
                }

                /*criação do array que contém dados que serão encaminhados para a model para inserção deles no bd.
                 é importante criar o array conforme as necessidades do bd e de acordo com a nomenclatura utilizada nele*/
                $arrayDados = array (
                    "nome"     => $dadosContato['txtNome'],
                    "telefone" => $dadosContato['txtTelefone'],
                    "celular"  => $dadosContato['txtCelular'],
                    "email"    => $dadosContato['txtEmail'],
                    "obs"      => $dadosContato['txtObs'],
                    "foto"     => $nomeFoto
                );
    
                //importar arquivo de manipulação de dados do bd
                require_once('model/bd/contato.php');
                //função presente na model
                if(insertContato($arrayDados)) {
                    return true;
                } else {
                    return array('idErro'  => 1,
                                 'message' => 'Não foi possível inserir dados no banco');
                }
            } else {
                return array('idErro'  => 2,
                             'message' => 'Há campos obrigatórios não preenchidos');
            } 
        }
    }

    //função que procurará no banco o contato que deverá ser editado
    function buscarContato($id) {
        if ($id != 0 && !empty($id) && is_numeric($id)) {
            require_once('model/bd/contato.php');

            //solicitando a função da model(contato.php) que vai buscar os dados no banco
            $dados = selectByIdContato($id);

            //validando se existem dados a serem devolvidos
            if (!empty($dados)) {
                return $dados;
            } else {
                return false;
            }
        } else {
            return array('idErro'  => 4,
                         'message' => 'Não foi possível buscar registro. ID inválido.');
        }
    }

    //função para receber os dados da view e encaminhar para a model (atualizar)
    function atualizarContato($dadosContato, $id) {
        if (!empty($dadosContato)) {
            //validando se as caixas de texto de nome e celular não estão vazias, pois o preenchimento é obrigatório no banco de dados
            if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {
                /* validando o id para garantir que ele seja válido */
                if($id != 0 && !empty($id) && is_numeric($id)) {
                    /*criação do array que contém dados que serão encaminhados para a model para inserção deles no bd.
                     é importante criar o array conforme as necessidades do bd e de acordo com a nomenclatura utilizada nele*/
                    $arrayDados = array (
                        "id"       => $id,
                        "nome"     => $dadosContato['txtNome'],
                        "telefone" => $dadosContato['txtTelefone'],
                        "celular"  => $dadosContato['txtCelular'],
                        "email"    => $dadosContato['txtEmail'],
                        "obs"      => $dadosContato['txtObs'],
                        "foto"     => $dadosContato['foto']
                    );
        
                    //importar arquivo de manipulação de dados do bd
                    require_once('model/bd/contato.php');
                    //função presente na model
                    if(updateContato($arrayDados)) {
                        return true;
                    } else {
                        return array('idErro'  => 1,
                                     'message' => 'Não foi possível editar dados no banco.');
                    }
                } else {
                    return array('idErro'  => 4,
                                 'message' => 'Não foi possível editar registro. ID inválido ou não inserido.');
                } 
            } else {
                return array('idErro'  => 2,
                             'message' => 'Algum campo obrigatório não preenchido.');
            }
        }
    }

    //função para realizar a exclusão de um contato (excluir)
    function deletarContato($id) {
        //verificando se o id é válido; diferente de zero, existente e númerico respectivamente
        if($id != 0 && !empty($id) && is_numeric($id)) {
            //import da model
            require_once('model/bd/contato.php');

            //chamando a função da model e verificando se o retorno foi true/false e exibindo mensagens em caso de erro
            if(deleteContato($id)) {
                return true;
            } else {
                return array('idErro'  => 3,
                             'message' => 'O banco não conseguiu deletar registro.');
            }
        } else {
            return array('idErro'  => 4,
                         'message' => 'Não foi possível excluir registro. ID inválido ou não inserido.');
        }
    }

    //função para solicitar os dados da model e encaminhar a lista de contatos para a view (inserir)
    function listarContato() {
            //import do arquivo que busca dados no banco
            require_once('model/bd/contato.php');

            //chamando a função que busca os dados no banco e armazenando-a em uma variável para uso posterior
            $dados = selectAllContatos();
            if(!empty($dados)) {
                return $dados;
            } else {
                return false;
            }
    }


?>