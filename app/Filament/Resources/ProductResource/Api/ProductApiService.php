<?php
namespace App\Filament\Resources\ProductResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ProductResource;
use Illuminate\Routing\Router;


class ProductApiService extends ApiService
{
    protected static string | null $resource = ProductResource::class;

    public static function allRoutes(Router $router)
    {
        Handlers\CreateHandler::route($router);
        Handlers\UpdateHandler::route($router);
        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
