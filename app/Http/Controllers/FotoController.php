<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Like;
use App\Models\Dislike;
use App\Models\GalleryLikeLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FotoController extends Controller
{
    /**
     * Toggle like for a photo
     */
    public function toggleLike(Request $request, $id)
    {
        try {
            Log::info('Toggle like request received', [
                'foto_id' => $id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk memberi like.',
                ], 401);
            }
            
            $foto = Foto::findOrFail($id);
            Log::info('Foto found', ['foto_id' => $foto->id, 'current_likes' => $foto->likes]);
            
            // Gunakan user yang sudah login
            $userId = Auth::id();
            Log::info('User ID', ['user_id' => $userId]);
            
            // Check if user already liked this photo
            $existingLike = Like::where('user_id', $userId)
                               ->where('foto_id', $foto->id)
                               ->first();
            
            Log::info('Existing like check', ['existing_like' => $existingLike ? $existingLike->toArray() : null]);
            
            if ($existingLike) {
                // Unlike - remove the like
                $existingLike->delete();
                $foto->decrement('likes');
                $liked = false;
                Log::info('Photo unliked', ['foto_id' => $id, 'user_id' => $userId]);

                // Log aksi unlike
                GalleryLikeLog::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id,
                    'action' => 'unlike',
                ]);
            } else {
                // Like - add the like
                $like = Like::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id
                ]);
                Log::info('Like created', ['like' => $like->toArray()]);
                $foto->increment('likes');
                $liked = true;
                Log::info('Photo liked', ['foto_id' => $id, 'user_id' => $userId]);

                // Log aksi like
                GalleryLikeLog::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id,
                    'action' => 'like',
                ]);

                // If user previously disliked, remove the dislike (mutually exclusive)
                $existingDislike = Dislike::where('user_id', $userId)
                    ->where('foto_id', $foto->id)
                    ->first();

                if ($existingDislike) {
                    $existingDislike->delete();
                    if ($foto->dislikes > 0) {
                        $foto->decrement('dislikes');
                    }
                    Log::info('Previous dislike removed after like', ['foto_id' => $id, 'user_id' => $userId]);

                    // Optional: log penghapusan dislike setelah like
                    GalleryLikeLog::create([
                        'user_id' => $userId,
                        'foto_id' => $foto->id,
                        'action' => 'undislike',
                    ]);
                }
            }
            
            // Refresh the foto model to get updated likes count
            $foto->refresh();
            
            return response()->json([
                'success' => true,
                'likes' => $foto->likes,
                'dislikes' => $foto->dislikes,
                'liked' => $liked
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling like', [
                'error' => $e->getMessage(),
                'foto_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui like: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle dislike for a photo
     */
    public function toggleDislike(Request $request, $id)
    {
        try {
            Log::info('Toggle dislike request received', [
                'foto_id' => $id,
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
            ]);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk memberi dislike.',
                ], 401);
            }

            $foto = Foto::findOrFail($id);
            Log::info('Foto found for dislike', ['foto_id' => $foto->id, 'current_dislikes' => $foto->dislikes]);

            $userId = Auth::id();

            // Check if user already disliked this photo
            $existingDislike = Dislike::where('user_id', $userId)
                ->where('foto_id', $foto->id)
                ->first();

            if ($existingDislike) {
                // Undo dislike
                $existingDislike->delete();
                $foto->decrement('dislikes');
                $disliked = false;
                Log::info('Photo undisliked', ['foto_id' => $id, 'user_id' => $userId]);

                // Log aksi undislike
                GalleryLikeLog::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id,
                    'action' => 'undislike',
                ]);
            } else {
                // Add dislike
                $dislike = Dislike::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id,
                ]);
                Log::info('Dislike created', ['dislike' => $dislike->toArray()]);
                $foto->increment('dislikes');
                $disliked = true;
                Log::info('Photo disliked', ['foto_id' => $id, 'user_id' => $userId]);

                // Log aksi dislike
                GalleryLikeLog::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id,
                    'action' => 'dislike',
                ]);

                // If user previously liked, remove the like (mutually exclusive)
                $existingLike = Like::where('user_id', $userId)
                    ->where('foto_id', $foto->id)
                    ->first();

                if ($existingLike) {
                    $existingLike->delete();
                    if ($foto->likes > 0) {
                        $foto->decrement('likes');
                    }
                    Log::info('Previous like removed after dislike', ['foto_id' => $id, 'user_id' => $userId]);

                    // Optional: log penghapusan like setelah dislike
                    GalleryLikeLog::create([
                        'user_id' => $userId,
                        'foto_id' => $foto->id,
                        'action' => 'unlike',
                    ]);
                }
            }

            $foto->refresh();

            return response()->json([
                'success' => true,
                'likes' => $foto->likes,
                'dislikes' => $foto->dislikes,
                'disliked' => $disliked,
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling dislike', [
                'error' => $e->getMessage(),
                'foto_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui dislike: ' . $e->getMessage(),
            ], 500);
        }
    }
}