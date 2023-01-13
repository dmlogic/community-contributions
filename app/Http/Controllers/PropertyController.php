<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class PropertyController extends Controller
{
    public function list(Request $request): View
    {
        return view('property.list', [
            'properties' => Property::with('resident')->get(),
        ]);
    }

    public function edit(Request $request, Property $property): View
    {
        return view('property.edit', [
            'property' => $property,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
