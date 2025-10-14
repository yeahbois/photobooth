<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Photo;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $photos = Photo::all();
        return view('gallery', compact('photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);

        $photo = new Photo();
        $photo->image = $request->image;
        $photo->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();
        return redirect()->route('gallery');
    }
}
