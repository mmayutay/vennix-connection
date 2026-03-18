<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryStoreRequest;
use App\Http\Requests\InventoryUpdateRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        $inventories = $query->orderByDesc('created_at')->paginate(10);

        logger($inventories);
        return Inertia::render('Inventory/Index', [
            'inventories' => InventoryResource::collection($inventories),
            'pagination' => [
                'current_page' => $inventories->currentPage(),
                'from' => $inventories->firstItem(),
                'to' => $inventories->lastItem(),
                'total' => $inventories->total(),
                'prev_page_url' => $inventories->previousPageUrl(),
                'next_page_url' => $inventories->nextPageUrl(),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {
        $inventory = Inventory::create($request->validated());

        return redirect()->route('inventory.index')->with('success', 'Inventory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory): InventoryResource
    {
        return new InventoryResource($inventory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryUpdateRequest $request, Inventory $inventory): InventoryResource
    {
        $inventory->update($request->validated());

        return new InventoryResource($inventory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory): Response
    {
        $inventory->delete();

        return response()->noContent();
    }
}

