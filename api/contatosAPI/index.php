<?php
// Import do arquivo autoload, que fara as instancias do slim
require_once('vendor/autoload.php');

// Criando um objeto do slim chamado app
$app = new \Slim\App();


/**
 * $request - Recebe dados do corpo da requisição (JSON, FORM/DATA, XML, etc)
 * $response - Envia dados de retorno da API
 * $args - Permite receber dados de atributos na API
 */



/**
 *  EndPoint: Requisição para Listar todos os Contatos
 * @return JSON Retorna o status 200 caso tenha dados para retornar
 *              ou Retorna o status 404 caso não tenha dados
 */
$app->get('/contatos', function ($request, $response, $args) {
    // Import da controller de contatos, que fará a busca de dados
    require_once('../modulo/config.php');
    require_once('../controllers/controllerContatos.php');

    // Solicita os dados para manipular a controller
    if ($dados = listaContato()) {
        // Realiza a conversão do array de dados em formato JSON
        if ($dadosJSON = createJSON($dados))
            // Caso exista dados a serem retornados, informamos o statusCode 200 e enviamos um JSON com os dados encontrados 
            return $response->write($dadosJSON)
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
    } else
        // Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
        return $response #->write('{message: "Não há dados disponíveis"}')
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(204);
});

// EndPoint: Requisição para Listar Contato pelo ID
$app->get('/contatos/{id}', function ($request, $response, $args) {
    // Import da controller de contatos, que fará a busca de dados
    require_once('../modulo/config.php');
    require_once('../controllers/controllerContatos.php');

// Recebe o ID do registro que deverá ser retornado pela API
// Esse ID esta chegando pela variável criada no endpoint
    $id = $args['id'];

    // Solicita os dados para manipular a controller
    if ($dados = buscarContato($id)) {
        // Verificando se houve algum tipo de erro no retorno dos dados da controller
        if(!isset($dados['idErro'])) {
            // Realiza a conversão do array de dados em formato JSON
            if ($dadosJSON = createJSON($dados))
                // Caso exista dados a serem retornados, informamos o statusCode 200 e enviamos um JSON com os dados encontrados 
                return $response->write($dadosJSON)
                                ->withHeader('Content-Type', 'application/json')
                                ->withStatus(200);
        } else {
            // Realiza a conversão do array de dados em formato JSON
            $dadosJSON = createJSON($dados);

            // Retorna um erro que significa que o cliente passou dados errados
            return $response->write('{"message": "Dados inválidos", "Erro": '. $dadosJSON .'}')
                            ->withHeader('Content-Type', 'application/json')
                            ->withStatus(404);
        }               
    } else
        // Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
        return $response #->write('{message: "Não há dados disponíveis"}')
                        ->withHeader('Content-Type', 'application/json')
                        ->withStatus(204);
});

// EndPoint: Requisição para deletar um Contato pelo ID
$app->delete('/contatos/{id}', function ($request, $response, $args) {
    if(is_numeric($args['id'])) {
        // Recebe o ID do registro que deverá ser retornado pela API
        // Esse ID esta chegando pela variável criada no endpoint
        $id = $args['id'];

        // Import da controller de contatos, que fará a busca de dados
        require_once('../modulo/config.php');
        require_once('../controllers/controllerContatos.php');


        // Busca o nome da foto para ser excluída na controller
        if($dados = buscarContato($id)){
            // Recebe o nome da foto que a controller retornou
            $foto = $dados['foto'];

            // Cria um array com o nome da foto e o ID para a controller excluir o registro
            $arrayDados = array(
                "id"    => $id,
                "foto"  => $foto
            );

            // Chama a função de excluir o contato, encaminhando o array com o ID e a foto
            $resposta = excluirContato($arrayDados);
            if(is_bool($resposta) && $resposta){
                // Retorna um erro que significa que o cliente informou um ID inválido
                return $response->write('{"message": "Registro excluído com sucesso!"}')
                                ->withHeader('Content-Type', 'application/json')
                                ->withStatus(200);
            } elseif(is_array($resposta) && isset($resposta['idErro'])) {
                $statusCode = 404;

                // Validação referente ao erro 5 que signifca que o registro foi excluido do BD e a imagem não existia no servidor
                if($resposta['idErro'] == 5) 
                    $statusCode = 200;

                // Realiza a conversão do array de dados em formato JSON
                $dadosJSON = createJSON($resposta);

                // Retorna um erro que significa que o cliente passou dados errados
                return $response->write('{"message": "Houve um problema no processo de excluir.", "Erro": '. $dadosJSON .'}')
                                ->withHeader('Content-Type', 'application/json')
                                ->withStatus($statusCode);
            }
        } else {
                // Retorna um erro que significa que o cliente informou um ID inválido
                return $response->write('{"message": "O ID informado não existe na base de dados."}')
                                ->withHeader('Content-Type', 'application/json')
                                ->withStatus(404);
        }
    } else {
            // Retorna um erro que significa que o cliente passou dados errados
            return $response->write('{"message": "É obrigatório informar um ID com um formato válido (número)."}')
                            ->withHeader('Content-Type', 'application/json')
                            ->withStatus(404);
    }
});


// EndPoiint: Requisição que envia dados para Criar contato
$app->post('/contatos', function($request, $response, $args){
        // Import da controller de contatos, que fará a busca de dados
        require_once('../modulo/config.php');
        require_once('../controllers/controllerContatos.php');

        $body = $request->getParsedBody();

        var_dump($body);
        die;
});

$app->get('/estados', function ($request, $response, $args) {
    // Import da controller de contatos, que fará a busca de dados
    require_once('../modulo/config.php');
    require_once('../controllers/controllerEstados.php');

    $dados = listaEstado();
    $dataJSON = json_encode($dados);

    $response->getBody()->write($dataJSON);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(204);
});



$app->run();
