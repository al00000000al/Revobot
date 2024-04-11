<?php

namespace Revobot\Handlers;

use Revobot\Config;
use Revobot\RequestHandlerInterface;
use Revobot\Response;
use Revobot\Util\PMC;

class StableDiffusionHandler implements RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri)
    {
        if (isset($_GET['key'])) {
            $key = Config::get('stable_diffusion_task_key');
            if (empty($key)) {
                return Response::json(['error' => 'key not set']);
            }
            if (($_GET['key'] === $key)) {
                $items = PMC::get('stable_diffusion_#');
                if (!empty($items)) {
                    $key = array_key_first($items);
                    PMC::delete('stable_diffusion_' . $key);
                    return Response::json($items[$key]);
                } else {
                    return Response::json([]);
                }
            } else {
                return Response::json(['error' => 'no access']);
            }
        }
    }
}
