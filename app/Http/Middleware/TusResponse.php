<?php

namespace App\Http\Middleware;

use Closure;
use TusPhp\Request;
use TusPhp\Response;
use TusPhp\Middleware\TusMiddleware;

class TusResponse implements TusMiddleware
{
  /**
   * {@inheritDoc}
   */
  public function handle(Request $request, Response $reponse)
  {
    if ($request->isPartial() && $request->isFinal()) {
      dd($request->extractAllMeta());
    }

    if ($request->isFinal()) {
      dd('is Final', $request->extractAllMeta());
    }
    return $reponse;
  }
}
