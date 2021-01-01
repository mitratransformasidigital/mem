<?php

namespace MEM\prjMitralPHP;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Top_10_Days controller
 */
class Top10DaysController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Top10DaysSummary");
    }
}
