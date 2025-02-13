<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestAttachmentController extends Controller
{
    public function store(Request $request, $requestId)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,png,jpeg',
            'file_type' => 'required|integer',
            'file_name' => 'nullable|string'
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $userRequest = BookingRequest::find($requestId);

        if(!$userRequest) {
          return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        $file = $request->file('file');
        $file_path = $file->store('requestAttachment/attachments', 's3');
        $data = [
            'file_type' => $request->file_type,
            'file_path' => $file_path,
            'description' => $request->description,
            'file_name' => $request->file_name
        ];

        $userRequest->requestAttachments()->create($data);

        return $this->formatResponse('success', 'request-attachment-created', $data);
    }
}
