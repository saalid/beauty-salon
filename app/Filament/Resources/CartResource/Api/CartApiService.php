<?php
namespace App\Filament\Resources\CartResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CartResource;
use Illuminate\Routing\Router;


class CartApiService extends ApiService
{
    protected static string | null $resource = CartResource::class;

    public static function allRoutes(Router $router)
    {
        Handlers\CreateHandler::route($router);
        Handlers\UpdateHandler::route($router);
        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
