<?php

// Route untuk debugging - hapus setelah fix
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/debug/homepage', function () {
    $results = [];
    
    // Test 1: Check if tables exist
    try {
        $tables = DB::select('SHOW TABLES');
        $results['tables'] = 'OK - ' . count($tables) . ' tables found';
    } catch (\Exception $e) {
        $results['tables'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Test 2: Check galery table
    try {
        $galeryCount = DB::table('galery')->count();
        $results['galery_count'] = 'OK - ' . $galeryCount . ' records';
    } catch (\Exception $e) {
        $results['galery_count'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Test 3: Check posts table
    try {
        $postsCount = DB::table('posts')->count();
        $results['posts_count'] = 'OK - ' . $postsCount . ' records';
    } catch (\Exception $e) {
        $results['posts_count'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Test 4: Check agenda table
    try {
        $agendaCount = DB::table('agenda')->count();
        $results['agenda_count'] = 'OK - ' . $agendaCount . ' records';
    } catch (\Exception $e) {
        $results['agenda_count'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Test 5: Try the actual query
    try {
        $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
            ->join('posts', 'galery.post_id', '=', 'posts.id')
            ->where('galery.status', 'aktif')
            ->orderBy('posts.created_at', 'desc')
            ->select('galery.*')
            ->limit(5)
            ->get();
        $results['query_galleries'] = 'OK - ' . $latestGalleries->count() . ' galleries';
    } catch (\Exception $e) {
        $results['query_galleries'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Test 6: Try agenda query
    try {
        $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
            ->orderBy('order')
            ->limit(4)
            ->get();
        $results['query_agendas'] = 'OK - ' . $latestAgendas->count() . ' agendas';
    } catch (\Exception $e) {
        $results['query_agendas'] = 'ERROR: ' . $e->getMessage();
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

