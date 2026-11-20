<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use App\Models\Plant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlantController extends Controller
{
    /**
     * Display a paginated list of every plant in the inventory.
     */
    public function index(): View
    {
        $plants = Plant::latest()->paginate(10);

        return view('plants.index', compact('plants'));
    }

    /**
     * Show the form for creating a new plant.
     */
    public function create(): View
    {
        return view('plants.create', [
            'categories' => Plant::CATEGORIES,
            'statuses' => Plant::STOCK_STATUSES,
        ]);
    }

    /**
     * Store a newly created plant.
     */
    public function store(StorePlantRequest $request): RedirectResponse
    {
        // Creating through the relationship automatically stamps user_id
        // with the authenticated administrator, so a client cannot forge it.
        $plant = $request->user()->plants()->create($request->validated());

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Plant record created successfully.');
    }

    /**
     * Display a single plant record.
     */
    public function show(Plant $plant): View
    {
        return view('plants.show', compact('plant'));
    }

    /**
     * Show the form for editing a plant.
     */
    public function edit(Plant $plant): View
    {
        return view('plants.edit', [
            'plant' => $plant,
            'categories' => Plant::CATEGORIES,
            'statuses' => Plant::STOCK_STATUSES,
        ]);
    }

    /**
     * Update an existing plant record.
     */
    public function update(UpdatePlantRequest $request, Plant $plant): RedirectResponse
    {
        // user_id is intentionally not updated: ownership stays with the
        // original creator even when another administrator edits the record.
        $plant->update($request->validated());

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Plant record updated successfully.');
    }

    /**
     * Remove a plant record from the inventory.
     */
    public function destroy(Plant $plant): RedirectResponse
    {
        $plant->delete();

        return redirect()->route('plants.index')
            ->with('success', 'Plant record deleted successfully.');
    }
}
