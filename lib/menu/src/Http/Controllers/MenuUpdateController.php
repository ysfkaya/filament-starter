<?php

namespace Ysfkaya\Menu\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Ysfkaya\Menu\Http\Requests\MenuRequest;
use Ysfkaya\Menu\Menu;

class MenuUpdateController extends Controller
{
    use ValidatesRequests;

    /**
     * Update the menu items.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(MenuRequest $request, Menu $menu)
    {
        $menu->update($request);

        return response()->json([
            'success' => true,
            'status' => 'updated',
        ], Response::HTTP_CREATED);
    }
}
