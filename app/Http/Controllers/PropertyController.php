<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PropertyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class PropertyController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('Property/List', [
            'properties' => Property::with('resident')
                                  ->select('id', 'number', 'street', 'user_id')
                                  ->orderBy('number')
                                  ->get()
        ]);
    }

    public function show(Property $property): Response
    {
        return Inertia::render('Property/View', [
            'property' => $property->load('resident'),
            'residents' => User::select('id', 'name', 'email' )->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Property/Form', [
            'property' => new Property([
                'number' => '',
                'street' => config('app.default_address.steet'),
                'town' => config('app.default_address.town'),
                'postcode' => config('app.default_address.postcode'),
            ]),
            'residents' => User::select('id', 'name', 'email' )->get(),
        ]);
    }

    public function store(PropertyRequest $request): RedirectResponse
    {
        Property::create($request->propertyData());
        return Redirect::route('property.index')->with('success', 'Property created');
    }

    public function edit($id)
    {
        //
    }


    public function update(PropertyRequest $request, Property $property): RedirectResponse
    {
        $property->fill($request->propertyData())->save();
        return Redirect::route('property.index')->with('success', 'Property updated');
    }

    public function destroy(Property $property): RedirectResponse
    {
        if($property->resident) {
            throw ValidationException::withMessages(['user_id' => 'Property must be unoccupied']);
        }
        $property->delete();
        return Redirect::route('property.index')->with('success', 'Property deleted');
    }
}
