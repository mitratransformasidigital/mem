<?php

namespace MEM\prjMitralPHP;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * rpt_quotation_print controller
 */
class RptQuotationPrintController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RptQuotationPrintSummary");
    }
}
