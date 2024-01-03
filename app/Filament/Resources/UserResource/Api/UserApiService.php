<?php
namespace App\Filament\Resources\UserResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UserResource;
use Illuminate\Routing\Router;


class UserApiService extends ApiService
{
    protected static string | null $resource = UserResource::class;

    public static function allRoutes(Router $router)
    {
        Handlers\CreateHandler::route($router);
        Handlers\UpdateHandler::route($router);
        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
