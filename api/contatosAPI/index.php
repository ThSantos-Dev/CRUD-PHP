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

// EndPoint: Requisição para Listar Contatos pelo ID
$app->get('/contatos/{id}', function ($request, $response, $args) {
    // Import da controller de contatos, que fará a busca de dados
    require_once('../modulo/config.php');
    require_once('../controllers/controllerContatos.php');

    // Solicita os dados para manipular a controller
    if ($dados = buscarContato($args['id'])) {
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
