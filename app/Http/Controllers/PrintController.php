<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PrintQueue;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'photo_id' => 'required|exists:photos,id',
        ]);

        PrintQueue::create([
            'photo_id' => $request->photo_id,
        ]);

        return redirect()->back()->with('success', 'Photo added to print queue.');
    }
}