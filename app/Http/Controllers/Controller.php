<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Laravel 11 REST API Sample Project",
 *     version="1.0.0",
 *     description="Explore the capabilities of Laravel 11 by building a RESTful API. This sample project demonstrates best practices and key features such as the repository pattern, PHPUnit testing, and Swagger documentation.",
 *     @OA\Contact(
 *         name="Jomar Sulit",
 *         email="sulitjomar@gmail.com"
 *     )
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
