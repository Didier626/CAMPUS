<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        return view('resources.index', [
            'resources' => Resource::orderByDesc('is_active')->orderBy('name')->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Resource::create($this->validatedData($request));

        return back()->with('success', 'Recurso creado correctamente.');
    }

    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $resource->update($this->validatedData($request, $resource));

        return back()->with('success', 'Recurso actualizado correctamente.');
    }

    public function deactivate(Resource $resource): RedirectResponse
    {
        $resource->update(['is_active' => false]);

        return back()->with('success', 'Recurso desactivado; su historial se conserva.');
    }

    private function validatedData(Request $request, ?Resource $resource = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('resources', 'code')->ignore($resource)],
            'name' => ['required', 'string', 'max:150'],
            'resource_type' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:200'],
            'status' => ['required', Rule::in(['available', 'maintenance', 'out_of_service'])],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
