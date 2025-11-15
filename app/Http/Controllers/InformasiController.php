<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InformasiController extends Controller
{
    /**
     * Display the school identity information edit form
     */
    public function index()
    {
        try {
            $settings = [
                'profile_title' => null,
                'profile_content' => null,
                'profile_image' => null,
            ];
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                    $settings = [
                        'profile_title' => SiteSetting::where('key', 'profile_title')->first(),
                        'profile_content' => SiteSetting::where('key', 'profile_content')->first(),
                        'profile_image' => SiteSetting::where('key', 'profile_image')->first(),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Error loading site settings: ' . $e->getMessage());
            }
            
            return view('admin.informasi.index', compact('settings'));
        } catch (\Throwable $e) {
            \Log::error('InformasiController index error: ' . $e->getMessage());
            return view('admin.informasi.index', [
                'settings' => [
                    'profile_title' => null,
                    'profile_content' => null,
                    'profile_image' => null,
                ]
            ]);
        }
    }

    /**
     * Update school identity information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'profile_title' => 'required|string|max:255',
            'profile_content' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update profile title dengan cara yang lebih aman
        $profileTitle = SiteSetting::where('key', 'profile_title')->first();
        if ($profileTitle) {
            $profileTitle->value = $validated['profile_title'];
            $profileTitle->save();
        } else {
            $profileTitle = new SiteSetting();
            $profileTitle->key = 'profile_title';
            $profileTitle->value = $validated['profile_title'];
            $profileTitle->label = 'Judul Profil';
            $profileTitle->type = 'text';
            $profileTitle->group = 'profile';
            $profileTitle->save();
        }

        // Update profile content dengan cara yang lebih aman
        $profileContent = SiteSetting::where('key', 'profile_content')->first();
        if ($profileContent) {
            $profileContent->value = $validated['profile_content'];
            $profileContent->save();
        } else {
            $profileContent = new SiteSetting();
            $profileContent->key = 'profile_content';
            $profileContent->value = $validated['profile_content'];
            $profileContent->label = 'Konten Profil';
            $profileContent->type = 'textarea';
            $profileContent->group = 'profile';
            $profileContent->save();
        }

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            $oldImage = SiteSetting::where('key', 'profile_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }

            // Store new image
            $imagePath = $request->file('profile_image')->store('profile', 'public');
            
            // Update or create profile image setting dengan cara yang lebih aman
            $profileImage = SiteSetting::where('key', 'profile_image')->first();
            if ($profileImage) {
                $profileImage->value = $imagePath;
                $profileImage->save();
            } else {
                $profileImage = new SiteSetting();
                $profileImage->key = 'profile_image';
                $profileImage->value = $imagePath;
                $profileImage->label = 'Foto Profil Sekolah';
                $profileImage->type = 'image';
                $profileImage->group = 'profile';
                $profileImage->save();
            }
        }

        // Logging untuk memastikan data tersimpan
        Log::info('Informasi sekolah updated and saved permanently', [
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'title' => $validated['profile_title']
        ]);

        return redirect()->route('informasi.index')
            ->with('success', 'Informasi sekolah berhasil diperbarui dan akan tersimpan permanen!');
    }
}
