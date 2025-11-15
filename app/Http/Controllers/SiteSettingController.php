<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the settings grouped by group
     */
    public function index()
    {
        try {
            $settings = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                    $settings = SiteSetting::orderBy('group')->orderBy('order')->get()->groupBy('group');
                }
            } catch (\Exception $e) {
                \Log::error('Error loading site settings: ' . $e->getMessage());
            }
            
            return view('admin.site-settings.index', compact('settings'));
        } catch (\Throwable $e) {
            \Log::error('SiteSettingController index error: ' . $e->getMessage());
            return view('admin.site-settings.index', ['settings' => collect([])]);
        }
    }

    /**
     * Show the form for editing the specified setting
     */
    public function edit($id)
    {
        $setting = SiteSetting::findOrFail($id);
        
        return view('admin.site-settings.edit', compact('setting'));
    }

    /**
     * Display settings for a specific group
     */
    public function showGroup($group)
    {
        $groupLabels = [
            'home' => 'Beranda',
            'profile' => 'Profil',
            'vision_mission' => 'Visi & Misi',
            'contact' => 'Kontak',
            'information' => 'Informasi',
            'agenda' => 'Agenda',
            'footer' => 'Footer'
        ];
        
        $groupLabel = $groupLabels[$group] ?? ucfirst(str_replace('_', ' ', $group));
        
        // Jika grup adalah 'school_info', tampilkan hanya program keahlian, fasilitas, dan prestasi
        if ($group === 'school_info') {
            $groupLabel = 'Informasi Sekolah';
            $settings = SiteSetting::where('group', 'information')
                ->where(function($query) {
                    $query->where('key', 'like', 'expertise_%')
                          ->orWhere('key', 'like', 'facility_%')
                          ->orWhere('key', 'like', 'achievement_%')
                          ->orWhere('key', 'facilities_title')
                          ->orWhere('key', 'achievements_title');
                })
                ->orderBy('order')
                ->get();
        } else {
            $settings = SiteSetting::where('group', $group)
                ->orderBy('order')
                ->get();
        }
            
        if ($settings->isEmpty()) {
            abort(404, 'Grup pengaturan tidak ditemukan');
        }
        
        return view('admin.site-settings.group', compact('settings', 'group', 'groupLabel'));
    }
    
    /**
     * Update the specified setting
     */
    public function update(Request $request, $id)
    {
        $setting = SiteSetting::findOrFail($id);
        
        $rules = [
            'value' => 'required',
        ];

        // Handle image upload
        if ($setting->type === 'image' && $request->hasFile('value')) {
            $rules['value'] = 'required|image|mimes:jpeg,png,jpg,gif|max:5120';
        }

        $request->validate($rules);

        $value = $request->value;

        // Handle image upload
        if ($setting->type === 'image' && $request->hasFile('value')) {
            // Delete old image if exists
            if ($setting->value && file_exists(public_path('uploads/settings/' . $setting->value))) {
                unlink(public_path('uploads/settings/' . $setting->value));
            }

            // Create directory if not exists
            if (!file_exists(public_path('uploads/settings'))) {
                mkdir(public_path('uploads/settings'), 0755, true);
            }

            $file = $request->file('value');
            $filename = time() . '_' . $setting->key . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $value = $filename;
        }

        $setting->update([
            'value' => $value
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting berhasil diupdate'
            ]);
        }

        return redirect()->route('site-settings.index')
            ->with('success', 'Setting berhasil diupdate!');
    }

    /**
     * Update multiple settings at once
     */
    public function updateBulk(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if ($setting) {
                // Handle image upload
                if ($setting->type === 'image' && $request->hasFile("settings_files.{$key}")) {
                    // Delete old image if exists
                    if ($setting->value && file_exists(public_path('uploads/settings/' . $setting->value))) {
                        unlink(public_path('uploads/settings/' . $setting->value));
                    }

                    // Create directory if not exists
                    if (!file_exists(public_path('uploads/settings'))) {
                        mkdir(public_path('uploads/settings'), 0755, true);
                    }

                    $file = $request->file("settings_files.{$key}");
                    $filename = time() . '_' . $setting->key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/settings'), $filename);
                    $value = $filename;
                }

                $setting->update(['value' => $value]);
            }
        }

        return redirect()->route('site-settings.index')
            ->with('success', 'Semua setting berhasil diupdate!');
    }
}