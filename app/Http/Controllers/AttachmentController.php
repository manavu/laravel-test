<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attachment;

class AttachmentController extends Controller
{
    public function show($id)
    {
        $attachment = Attachment::find($id);

        $headers = [
            'Content-Type' => $attachment->contentType,
            'content-Disposition' => 'attachment; filename="' . $attachment->name . '"',
        ];

        return response()->make($attachment->data, 200, $headers);
    }
}
