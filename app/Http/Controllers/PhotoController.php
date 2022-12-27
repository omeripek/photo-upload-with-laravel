<?php

namespace App\Http\Controllers;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
       
         // Get the list of photos sorted by the order attribute
        $photos = Photo::orderBy('order')->get();

        // Render the view with the sorted photos
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

    public function updateOrder(Request $request)
    {
        // Validate the form data
        $request->validate([
            'order' => 'required|array',
        ]);
    
        // Get the list of sorted photos
        $sortedPhotos = $request->input('order');
    
        // Update the order of the photos in the database
        foreach ($sortedPhotos as $index => $photoId) {
            $photo = Photo::find($photoId);
            $photo->order = $index + 1;
            $photo->save();
        }
    
        // Return a success response
        return response()->json([
            'success' => true,
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
