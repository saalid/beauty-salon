<?php
namespace App\Filament\Resources\CategoryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CategoryResource;
use Illuminate\Routing\Router;


class CategoryApiService extends ApiService
{
    protected static string | null $resource = CategoryResource::class;

    public static function allRoutes(Router $router)
    {
        Handlers\CreateHandler::route($router);
        Handlers\UpdateHandler::route($router);
        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
