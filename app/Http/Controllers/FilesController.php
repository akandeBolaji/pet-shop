<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FilesController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/file/upload",
     *      operationId="CreateFile",
     *      tags={"Files"},
     *      summary="Upload a file",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={
     *                      "file",
     *                  },
     *                  @OA\Property(property="file", type="file"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Upload file
     */
    public function store(FileRequest $request): JsonResponse
    {
        $uploaded_file = $request->file('file');
    
        // Assert that we have an instance of UploadedFile
        if (!($uploaded_file instanceof UploadedFile)) {
            // Handle this case, maybe throw an error or return a response
            return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'No valid file uploaded.');
        }
    
        $filename = 'pet-shop/' . Str::random(40) . '.' . $uploaded_file->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs('', $uploaded_file, $filename);
        $file = Storage::disk('public')->get($filename);
    
        $record = new File([
            'name' => Str::random(16),
            'type' => $uploaded_file->getMimeType(),
            'size' => round(Storage::size($filename) / 1024) . ' KB',
            'path' => $path,
        ]);
    
        if ($record->save()) {
            return $this->jsonResponse(data: new FileResource($record));
        }
    
        // Delete the file if saving the record fails
        Storage::disk('public')->delete($filename);
    
        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Failed to store file data.');
    }
    

    /**
     * @OA\Get(
     *      path="/api/v1/file/{uuid}",
     *      operationId="showFile",
     *      tags={"Files"},
     *      summary="Fetch a file",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *      ),
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Display the specified file
     */
    public function show(File $file): JsonResponse
    {
        return $this->jsonResponse(data: new FileResource($file));
    }
}
