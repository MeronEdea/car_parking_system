<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Function to register custome cars
     */
    public function registerCar(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
            'model'=> 'required|string|max:50',
        ]);

        $car = Car::create([
            'user_id'=>auth()->id,
            'plate_number'=>$request->plate_number,
            'model'=> $request->model,
        ]);

        return redirect()->back()->with('success', 'Car Registered Successfully.');
    }

    /**
     * function that lists all cars to the admin.
     */
    public function listCars()
    {
        $cars = Car::all();
        return view ('admin.cars', compact ('cars'));
    }

    /**
     * function that lists all users to the admin.
     */
    public function myCars()
    {
        $cars = auth()->user()->cars;
        return view ('cars.my_cars', compact ('cars'));
    }
}
