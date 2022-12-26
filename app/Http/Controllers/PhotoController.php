<?php

namespace App\Http\Controllers;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
       // $photos = Photo::orderBy('order')->get();
       // return view('photos', compact('photos'));
        $photos = Photo::all();
        return view('photos', ['photos' => $photos]);
    }


    public function destroy(Request $request)
    {
        $id = $request->photo;
        // Find the image from the database
        $photo = Photo::findOrFail($id);

        // Delete the image from the folder
        Storage::delete('public/' . $photo->path);

        // Delete the image data from the database
        $photo->delete();

        return response()->json([
            'success' => 'Photo deleted successfully!'
        ]);
    }

    public function order(Request $request)
    {
        $photos = json_decode($request->photos);

        foreach ($photos as $index => $photo) {
            Photo::where('id', $photo->id)
                ->update(['position' => $index]);
        }

        return response()->json([
            'success' => 'Photos ordered successfully'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Save the image to a folder
        $image = $request->file('photo');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $filename);

        // Save the image data to the database
        $photo = new Photo();
        $photo->path = 'images/' . $filename;
        $photo->save();

        // Response json data
        return response()->json([
            'success' => 'Photo uploaded Successfully!',
            'id' => $photo->id,
            'image' => $photo->path,
        ]);
    }

    public function list()
    {
        $photos = Photo::orderBy('order')->get();

        return response()->json([
            'photos' => $photos
        ]);
    }
}
