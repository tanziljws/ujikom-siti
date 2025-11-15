<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgendaController extends Controller
{
    public function index()
    {
        try {
            $agenda = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                    $agenda = Agenda::orderBy('order')->orderBy('created_at', 'desc')->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading agenda: ' . $e->getMessage());
            }
            
            return view('admin.agenda.index', compact('agenda'));
        } catch (\Throwable $e) {
            \Log::error('AgendaController index error: ' . $e->getMessage());
            return view('admin.agenda.index', ['agenda' => collect([])]);
        }
    }

    public function create()
    {
        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_label' => 'required|string|max:100',
            'time' => 'required|string|max:100',
            'event_date' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif',
            'order' => 'nullable|integer|min:0'
        ]);

        // Simpan agenda dengan aman
        $agenda = new Agenda();
        $agenda->title = $validated['title'];
        $agenda->description = $validated['description'];
        $agenda->date_label = $validated['date_label'];
        $agenda->time = $validated['time'];
        $agenda->event_date = $validated['event_date'];
        $agenda->status = $validated['status'];
        $agenda->order = $validated['order'] ?? 0;
        $agenda->save();

        // Logging untuk memastikan data tersimpan
        Log::info('Agenda created and saved permanently', [
            'agenda_id' => $agenda->id, 
            'title' => $agenda->title,
            'created_at' => $agenda->created_at->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan dan akan tersimpan permanen!');
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('admin.agenda.edit', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_label' => 'required|string|max:100',
            'time' => 'required|string|max:100',
            'event_date' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif',
            'order' => 'nullable|integer|min:0'
        ]);

        // Update agenda dengan aman
        $agenda->title = $validated['title'];
        $agenda->description = $validated['description'];
        $agenda->date_label = $validated['date_label'];
        $agenda->time = $validated['time'];
        $agenda->event_date = $validated['event_date'];
        $agenda->status = $validated['status'];
        $agenda->order = $validated['order'] ?? $agenda->order;
        $agenda->save();

        // Logging untuk memastikan data diupdate
        Log::info('Agenda updated and saved permanently', [
            'agenda_id' => $agenda->id, 
            'title' => $agenda->title,
            'updated_at' => $agenda->updated_at->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil diperbarui dan akan tersimpan permanen!');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        
        // Logging sebelum menghapus
        Log::info('Agenda manually deleted by user', [
            'agenda_id' => $agenda->id, 
            'title' => $agenda->title,
            'deleted_at' => now()->format('Y-m-d H:i:s')
        ]);
        
        $agenda->delete();

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dihapus!');
    }
}