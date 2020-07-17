<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;


use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\History\HistoryDatabase;

class HistoryController
{
    protected $historyDatabase;
    protected $response;

    function __construct(HistoryDatabase $historyDatabase)
    {
        $this->historyDatabase = $historyDatabase;

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

    public function show()
    {
        dd('create show history by id here');
    }

    public function remove()
    {
        // todo: modify codes to remove history
        dd('create remove history logic here');
    }
}
