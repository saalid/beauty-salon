<?php
namespace App\Filament\Resources\TeacherResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TeacherResource;
use Illuminate\Routing\Router;


class TeacherApiService extends ApiService
{
    protected static string | null $resource = TeacherResource::class;

    public static function allRoutes(Router $router)
    {
        Handlers\CreateHandler::route($router);
        Handlers\UpdateHandler::route($router);
        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
