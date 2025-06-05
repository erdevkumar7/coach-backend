<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
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

    public function formStore(Request $request)
    {        
        try {
            $data = $request->validate([
                'form_id'   => 'required|string|max:255',
                'page_id'   => 'nullable|string',
                'user_id'   => 'nullable|string',
                'form_data' => 'required|array',
            ]);

            $submission = FormSubmission::create([
                'form_id'   => $data['form_id'],
                'page_id'   => $data['page_id'] ?? null,
                'user_id'   => $data['user_id'] ?? null,
                'form_data' => $data['form_data'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully.',
                'data' => $submission,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(), // optional: comment this out in production
            ], 500);
        }
    }

    public function FormShow($id)
    {
        $form = FormSubmission::find($id);

        if (!$form) {
            return response()->json(['error' => 'form not found'], 404);
        }

        return response()->json($form);
    }
}
