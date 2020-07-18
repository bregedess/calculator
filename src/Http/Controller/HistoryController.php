<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;


use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\History\HistoryDatabase;
use Jakmall\Recruitment\Calculator\History\HistoryFile;

class HistoryController
{
    protected $historyDatabase;
    protected $historyFile;
    protected $response;

    public function __construct(HistoryDatabase $historyDatabase, HistoryFile $historyFile)
    {
        $this->historyDatabase = $historyDatabase;
        $this->historyFile = $historyFile;

        $this->response = new Response();
        $this->response->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    public function index()
    {
        $dataSet = $this->historyDatabase->findAll();

        $this->response->setContent(json_encode($dataSet));
        $this->response->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this->response;
    }

    public function show($id)
    {
        $dataSet = $this->historyDatabase->find($id);

        if (!$dataSet) {
            $this->response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $this->response;
        }

        $this->response->setContent(json_encode($dataSet));
        $this->response->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this->response;
    }

    public function remove($id)
    {
        $this->historyDatabase->delete($id);
        $this->historyFile->delete($id);

        $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
        $this->response->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this->response;
    }
}
