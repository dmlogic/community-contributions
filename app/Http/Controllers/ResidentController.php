<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Property;
use App\Models\Resident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ResidentCreateRequest;
use App\Http\Requests\ResidentUpdateRequest;
use Illuminate\Validation\ValidationException;

class ResidentController extends Controller
{

    /**
     * @route resident.index
     */
    public function index(): Response
    {
        return Inertia::render('Resident/List', [
            'residents' => Resident::with('property')->get()
        ]);
    }

    /**
     * @route resident.show
     */
    public function show(Resident $resident): Response
    {
        return Inertia::render('Resident/View', [
            'resident' => $resident->load('property')
        ]);
    }

    /**
     * @route resident.edit
     */
    public function edit(Resident $resident): Response
    {
        return $this->renderResidentForm($resident);
    }

    /**
     * @route resident.create
     */
    public function create(): Response
    {
        return $this->renderResidentForm(new Resident);
    }

    /**
     * @route resident.store
     */
    public function store(ResidentCreateRequest $request): RedirectResponse
    {
        Resident::create($request->formData());
        return Redirect::route('resident.index')
                       ->with('success', 'Resident created');
    }

    /**
     * @route resident.update
     */
    public function update(ResidentUpdateRequest $request, Resident $resident): RedirectResponse
    {
        $resident->fill($request->formData())->save();
        return Redirect::route('resident.index')
                       ->with('success', 'Resident updated');
    }

    /**
     * @route resident.destroy
     */
    public function destroy(Resident $resident): RedirectResponse
    {
        $resident->delete();
        return Redirect::route('resident.index')
                       ->with('success', 'Resident deleted');
    }

    /**
     * Helper to share the creation of a property form
     * Vue will handle logic changes based on presence of resident.id
     */
    private function renderResidentForm(Resident $resident): Response
    {
        return Inertia::render('Resident/Form', [
            'resident' => $resident,
            'properties' => Property::listData()
        ]);
    }
}
