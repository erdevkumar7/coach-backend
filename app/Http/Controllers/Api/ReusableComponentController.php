<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReusableComponent;
use Illuminate\Http\Request;

class ReusableComponentController extends Controller
{
    public function index()
    {
        return response()->json(ReusableComponent::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'html' => 'required|string',
            'css' => 'nullable|string',
            'components' => 'required|json',
        ]);

        $component = ReusableComponent::create($data);

        return response()->json([
            'success' => true,
            'data' => $component,
            'message' => 'Reusable component created successfully.'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $component = ReusableComponent::find($id);

        if (!$component) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'html' => 'sometimes|required|string',
            'css' => 'nullable|string',
            'components' => 'sometimes|required|json',
        ]);

        $component->update($data);

        return response()->json([
            'success' => true,
            'data' => $component,
            'message' => 'Component updated successfully.'
        ]);
    }

    public function show($id)
    {
        $component = ReusableComponent::find($id);

        if (!$component) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        return response()->json($component);
    }

    public function destroy($id)
    {
        $component = ReusableComponent::find($id);

        if (!$component) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        $component->delete();

        return response()->json([
            'success' => true,
            'message' => 'Component deleted successfully.'
        ]);
    }
}
