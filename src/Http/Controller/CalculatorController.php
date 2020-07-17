<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class CalculatorController
{
    /** @var \Illuminate\Console\Application $app */
    protected $app;
    protected $response;

    function __construct()
    {
        $this->app = require __DIR__.'/../../../bootstrap/app.php';

        $this->response = new Response();
        $this->response->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    public function calculate(Request $request, $action)
    {
        $input = $request->input('input');

        try {
            if ($action == 'pow') {
                $this->app->call($action, ['base' => $input[0], 'exp' => $input[1]]);
            } else {
                $this->app->call($action, ['numbers' => $input]);
            }

        } catch (CommandNotFoundException $e) {
            return $this->throwError($e);
        } catch (\InvalidArgumentException $e) {
            return $this->throwError($e);
        }

        $output = explode('=', $this->app->output());

        $result = collect([
            'command' => $action,
            'operation' => $output[0],
            'result' => (int) $output[1],
        ]);

        $this->response->setContent($result->toJson());
        return $this->response;
    }

    protected function throwError($e) {
        $result['error'] = true;
        $result['message'] = $e->getMessage();

        $this->response->setStatusCode(400);
        $this->response->setContent(json_encode($result));
        return $this->response;
    }
}
