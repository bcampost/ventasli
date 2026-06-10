<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentacionController extends Controller
{
    public function index()
    {
        Documento::query()
            ->where('estatus', 'Vigente')
            ->whereNotNull('vigencia')
            ->where('vigencia', '<', now()->toDateString())
            ->update(['estatus' => 'Vencido']);

        $documentos = Documento::orderByDesc('id')->get();

        return view('documentacion.index', compact('documentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria' => ['required', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:255'],
            'vigencia' => ['nullable', 'date'],
            'actualizado_en' => ['nullable', 'date'],
            'actualizado_por' => ['nullable', 'string', 'max:255'],
            'responsable' => ['nullable', 'string', 'max:255'],
            'archivo' => ['nullable', 'file', 'max:10240'],
        ]);

        $data['estatus'] = !empty($data['vigencia']) && $data['vigencia'] < now()->toDateString()
            ? 'Vencido'
            : 'Vigente';

        unset($data['periodo']);

        if ($request->hasFile('archivo')) {
            $data['archivo_path'] = $request->file('archivo')->store('documentacion', 'public');
        }

        Documento::create($data);

        return redirect()->route('documentacion.index')->with('ok', 'Documento agregado.');
    }

    public function update(Request $request, Documento $documento)
    {
        $data = $request->validate([
            'categoria' => ['required', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:255'],
            'vigencia' => ['nullable', 'date'],
            'actualizado_en' => ['nullable', 'date'],
            'actualizado_por' => ['nullable', 'string', 'max:255'],
            'responsable' => ['nullable', 'string', 'max:255'],
            'archivo' => ['nullable', 'file', 'max:10240'],
        ]);

        $data['estatus'] = !empty($data['vigencia']) && $data['vigencia'] < now()->toDateString()
            ? 'Vencido'
            : 'Vigente';

        unset($data['periodo']);

        if ($request->hasFile('archivo')) {
            if ($documento->archivo_path && Storage::disk('public')->exists($documento->archivo_path)) {
                Storage::disk('public')->delete($documento->archivo_path);
            }

            $data['archivo_path'] = $request->file('archivo')->store('documentacion', 'public');
        }

        $documento->update($data);

        return redirect()->route('documentacion.index')->with('ok', 'Documento actualizado.');
    }

    public function destroy(Documento $documento)
    {
        if ($documento->archivo_path && Storage::disk('public')->exists($documento->archivo_path)) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('documentacion.index')->with('ok', 'Documento eliminado.');
    }
}