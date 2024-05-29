<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $rules = ['image' => 'required|image|mimes:jpeg,png,jpg,gif'];
            $request->validate($rules);
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imageSize = $image->getSize();
            if (session('module_active') == 'product') {
                $path = $image->move('public/uploads/images/admin/product', $imageName);
            } elseif (session('module_active') == 'user') {
                $path = $image->move('public/uploads/images/admin/user', $imageName);
            }


            // Save image path to database
            $data = [
                'url' => $path,
                'file_name' => $imageName,
                'file_size' => $imageSize,
                'user_id' => Auth::id()
            ];
            Image::create($data);
            $lastImage = Image::latest()->first();
            return [$lastImage->url, $lastImage->id];
        } else {
            return response()->json(['error' => 'No file selected.'], 400);
        }
    }
}
