<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PropertyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class PropertyController extends Controller
{

    /**
     * @route property.index
     */
    public function index(): Response
    {
        return Inertia::render('Property/List', [
            'properties' => Property::listData()
        ]);
    }

    /**
     * @route property.show
     */
    public function show(Property $property): Response
    {
        // we don't have a separate view/edit page
        return $this->edit($property);
    }

    /**
     * @route property.edit
     */
    public function edit(Property $property): Response
    {
        return $this->renderPropertyForm($property->load('resident'));
    }

    /**
     * @route property.create
     */
    public function create(): Response
    {
        return $this->renderPropertyForm(Property::defaultData());
    }

    /**
     * @route property.store
     */
    public function store(PropertyRequest $request): RedirectResponse
    {
        Property::create($request->propertyData());
        return Redirect::route('property.index')
                       ->with('success', 'Property created');
    }

    /**
     * @route property.update
     */
    public function update(PropertyRequest $request, Property $property): RedirectResponse
    {
        $property->fill($request->propertyData())->save();
        return Redirect::route('property.index')
                       ->with('success', 'Property updated');
    }

    /**
     * @route property.destroy
     */
    public function destroy(Property $property): RedirectResponse
    {
        if($property->resident) {
            throw ValidationException::withMessages(['user_id' => 'Property must be unoccupied']);
        }
        $property->delete();
        return Redirect::route('property.index')
                       ->with('success', 'Property deleted');
    }

    /**
     * Helper to share the creation of a property form
     * Vue will handle logic changes based on presence of property.id
     */
    private function renderPropertyForm(Property $property): Response
    {
        return Inertia::render('Property/Form', [
            'property' => $property,
            'residents' => User::residentData()
        ]);
    }
}
