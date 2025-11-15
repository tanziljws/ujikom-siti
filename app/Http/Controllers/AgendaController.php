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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date_label' => 'required|string|max:100',
                'time' => 'required|string|max:100',
                'event_date' => 'nullable|date',
                'status' => 'required|in:aktif,nonaktif',
                'order' => 'nullable|integer|min:0'
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                throw new \Exception('Table agenda does not exist');
            }

            $agenda = new Agenda();
            $agenda->title = $validated['title'];
            $agenda->description = $validated['description'];
            $agenda->date_label = $validated['date_label'];
            $agenda->time = $validated['time'];
            $agenda->event_date = $validated['event_date'];
            $agenda->status = $validated['status'];
            $agenda->order = $validated['order'] ?? 0;
            $agenda->save();

            Log::info('Agenda created', ['agenda_id' => $agenda->id]);

            return redirect()->route('agenda.index')->with('success', 'Agenda berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('AgendaController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating agenda: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                return redirect()->route('agenda.index')->with('error', 'Table agenda does not exist');
            }
            
            $agenda = Agenda::findOrFail($id);
            return view('admin.agenda.edit', compact('agenda'));
        } catch (\Throwable $e) {
            \Log::error('AgendaController edit error: ' . $e->getMessage());
            return redirect()->route('agenda.index')->with('error', 'Error loading agenda');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                throw new \Exception('Table agenda does not exist');
            }

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

            $agenda->title = $validated['title'];
            $agenda->description = $validated['description'];
            $agenda->date_label = $validated['date_label'];
            $agenda->time = $validated['time'];
            $agenda->event_date = $validated['event_date'];
            $agenda->status = $validated['status'];
            $agenda->order = $validated['order'] ?? $agenda->order;
            $agenda->save();

            Log::info('Agenda updated', ['agenda_id' => $agenda->id]);

            return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('AgendaController update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating agenda: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                throw new \Exception('Table agenda does not exist');
            }

            $agenda = Agenda::findOrFail($id);
            
            Log::info('Agenda deletion started', ['agenda_id' => $agenda->id]);
            
            $agenda->delete();

            return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
        } catch (\Throwable $e) {
            \Log::error('AgendaController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting agenda: ' . $e->getMessage());
        }
    }
}