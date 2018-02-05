<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'bootstrap.php';

/**
 * Inicio do CRUD
 * @var string
 */
$app->get('/', function (Request $request, Response $response) use ($app) {

    $response->getBody()->write("Listagem de contatos");

    return $response;
});

/**
 * Lista de todos os contatos
 * @request curl -X GET http://localhost:8000/contact
 */
$app->get('/contact', function (Request $request, Response $response) use ($app) {

    $sqlStmt  = $this->database
                     ->select()
                     ->from('contacts');
    $stmt     = $sqlStmt->execute();
    $contacts = $stmt->fetchAll();

    $return = $response
                ->withJson($contacts, 200)
                ->withHeader('Content-type', 'application/json');

    return $return;
});

/**
 * Retornando os dados de contato informado pelo ID
 * @request curl -X GET http://localhost:8000/contact/1
 */
$app->get('/contact/{id}', function (Request $request, Response $response) use ($app) {
    $route  = $request->getAttribute('route');
    $id     = $route->getArgument('id');

    $sqlStmt = $this->database
                    ->select()
                    ->from('contacts')
                    ->where('id','=',$id);
    $stmt    = $sqlStmt->execute();
    $contact = $stmt->fetch();

    $return = $response
    			->withJson($contact, 200)
    			->withHeader('Content-type', 'application/json');

    return $return;
});

/**
 * Cadastra um novo contato
 * @request curl -X POST http://localhost:8000/contact -H "Content-type: application/json" -d '{"name":"Fulano", "phone":"(19) 99111-1111", "email":"fulano@email.com.br"}'
 */
$app->post('/contact', function (Request $request, Response $response) use ($app) {
    $params = (array) $request->getParams();

    $sqlStmt  = $this->database
                     ->insert(array_keys($params))
                     ->into('contacts')
                     ->values(array_values($params));
    $insertId = $sqlStmt->execute();
    $sqlStmt  = $this->database
                    ->select()
                    ->from('contacts')
                    ->where('id','=',$insertId);
    $stmt     = $sqlStmt->execute();
    $contact  = $stmt->fetch();

    $return = $response
                ->withJson($contact, 201)
                ->withHeader('Content-type', 'application/json');

    return $return;
});

/**
 * Atualiza os dados de um contato
 * @request curl -X PUT http://localhost:8000/contact/1 -H "Content-type: application/json" -d '{"name":"Fulano de Tal", "phone":"(19) 99222-1111", "email":"fulano@email.com"}'
 */
$app->put('/contact/{id}', function (Request $request, Response $response) use ($app) {
    $route  = $request->getAttribute('route');
    $id     = $route->getArgument('id');
    $params = (array) $request->getParams();

    $sqlStmt  = $this->database
                     ->update($params)
                     ->table('contacts')
                     ->where('id','=',$id);
    $stmt     = $sqlStmt->execute();
    $sqlStmt  = $this->database
                    ->select()
                    ->from('contacts')
                    ->where('id','=',$id);
    $stmt     = $sqlStmt->execute();
    $contact  = $stmt->fetch();

    $return = $response
                ->withJson($contact, 200)
                ->withHeader('Content-type', 'application/json');

    return $return;
});

/**
 * Deleta o livro informado pelo ID
 * @request curl -X DELETE http://localhost:8000/contact/1
 */
$app->delete('/contact/{id}', function (Request $request, Response $response) use ($app) {
    $route  = $request->getAttribute('route');
    $id     = $route->getArgument('id');

    $sqlStmt  = $this->database
                     ->delete()
                     ->from('contacts')
                     ->where('id','=',$id);
    $stmt     = $sqlStmt->execute();

    $return = $response
    			->withJson(array(), 204)
    			->withHeader('Content-type', 'application/json');

    return $return;
});

$app->run();
