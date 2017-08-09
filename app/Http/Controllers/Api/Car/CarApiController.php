<?php

namespace App\Http\Controllers\Api\Car;

use App\Http\Requests\CreateCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Requests\DeleteCarRequest;
use App\Http\Controllers\Controller;
use App\Services\CarService;

class CarApiController extends Controller
{

    private $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function create(CreateCarRequest $request)
    {
        $car = $this->carService->create($request);
        return response()->json(['success create' => true, $request->all(), $car]);
    }

    public function update(UpdateCarRequest $request)
    {
        return response()->json(['success update' => true, $request]);
    }

    public function delete(DeleteCarRequest $request)
    {
        return response()->json(['success delete' => true, $request->all()]);
    }
}