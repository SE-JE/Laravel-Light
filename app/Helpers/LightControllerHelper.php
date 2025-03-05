<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;

trait LightControllerHelper
{
    // =========================>
    // ## Get Params
    // =========================>
    public function getParams(Request $request)
    {
        return [
            'sortDirection' => $request->get('sortDirection', 'DESC'),
            'sortBy'        => $request->get('sortBy', 'created_at'),
            'paginate'      => $request->get('paginate', 10),
            'filter'        => $request->get('filter', null),
            'search'        => $request->get('search', null),
        ];
    }

    // =========================>
    // ## Validation
    // =========================>
    public function validation(
        array $request, //? http request body
        array $rules    //? validator rules
    ) {
        $validate = Validator::make($request, $rules);

        if ($validate->fails()) {
            response()->json([
                'message' => "Error: Unprocessable Entity!",
                'errors' => $validate->errors(),
            ], 422)->throwResponse();
        }
    }

    // =========================>
    // ## Response Error Handler
    // =========================>
    public function responseError(
        string $error,                   //? error description
        string|null $section = null,     //? where an error occurred?
        string|null $message = null,     //? custom message
    ) {
        if(env('APP_DEBUG')) {
            response()->json([
                'message' => $message ?? "Error: Server Side Having Problem!",
                'error' => $error ?? 'unknown',
                'section' => $section ?? 'unknown',
            ], 500)->throwResponse();
            
        } else {
            response()->json([
                'message' => $message ?? "Error: Server Side Having Problem!",
            ], 500)->throwResponse();
        }
    }

    // =========================>
    // ## Response Data Handler
    // =========================>
    public function responseData(
        array $data,                       //? data returned
        int|null $totalRow = null,         //? all total row
        string|null $message = null,       //? custom message
        array|null $columns = null,        //? custom default column
    ) {
        response()->json([
            'message' => $message ?? (count($data) ? 'Success' : 'Empty data'),
            'data' => $data ?? [],
            'total_row' => $totalRow ?? null,
            'columns' => $columns ?? null,
        ], count($data) ? 200 : 206)->throwResponse();
    }

    // =========================>
    // ## Response Saved Handler
    // =========================>
    public function responseSaved(
        array|stdClass $data,         //? http request body
        string|null $message = null,         //? custom message
    ) {
        response()->json([
            'message' => $message ?? 'Success',
            'data' => $data ?? [],
        ], 201)->throwResponse();
    }

    // =========================>
    // ## Upload file
    // =========================>
    public function uploadFile(
        \Illuminate\Http\UploadedFile $file,  //? file
        string $folder = ''                   //? storage folder name
    ) {
        return Storage::disk('private')->put($folder, $file);
    }

    // =========================>
    // ## Delete file
    // =========================>
    public function deleteFile(
        string $path  //? path to file
    ) {
        if (Storage::disk('private')->exists($path)) {
            Storage::disk('private')->delete($path);
        }
    }

    // =========================>
    // ## Response file
    // =========================>
    public function responseFile(
        string $path  //? path to file
    ) {
        $file_path = Storage::disk('private') . $path;

        if (Storage::disk('private')->exists($path)) {
            return response()->file($file_path);
        }

        return response(['message' => 'File not found'], 404);
    }
}