<?php

namespace MEM\prjMitralPHP;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * My_Dashboard controller
 */
class MyDashboardController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MyDashboard");
    }
}
