<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerPluginController extends Controller
{
    public function store(Request $request)
    {
        $stored_file = $request->UploadFiles
            ->storeAs('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT)
                , $request->file('UploadFiles')->getClientOriginalName());

        $attachment = Attachment::create([
            'name' => $request->file('UploadFiles')->getClientOriginalName(),
            'path' => $stored_file
        ]);
    }
}
