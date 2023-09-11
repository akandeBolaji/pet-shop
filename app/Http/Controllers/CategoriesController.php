<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/categories",
     *      operationId="categoryListing",
     *      tags={"Categories"},
     *      summary="List of categories",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="sort_by",
     *          in="query",
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="desc",
     *          in="query",
     *          @OA\Schema(
     *             type="boolean",
     *          ),
     *      ),
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $filter_params = $request->filterParams();
        $data = CategoryResource::collection(Category::getAll($filter_params))->resource;

        return $this->jsonResponse(data: $data);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/category/create",
     *      operationId="CreateCategory",
     *      tags={"Categories"},
     *      summary="Create a new category",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "title",
     *                  },
     *                  @OA\Property(property="title", type="string"),
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
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $inputs = $request->validFields();
        $inputs['slug'] = Str::slug(strval($request->title));

        $category = Category::create($inputs);

        return $this->jsonResponse(data: new CategoryResource($category));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/category/{uuid}",
     *      operationId="showCategory",
     *      tags={"Categories"},
     *      summary="Fetch a category",
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
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return $this->jsonResponse(data: new CategoryResource($category));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/category/{uuid}",
     *      operationId="UpdateCategory",
     *      tags={"Categories"},
     *      summary="Update an existing category",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "title",
     *                  },
     *                  @OA\Property(property="title", type="string"),
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
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $inputs = $request->validFields();
        $inputs['slug'] = Str::slug(strval($request->title));

        if ($category->update($inputs)) {
            return $this->jsonResponse(data: new CategoryResource($category));
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/category/{uuid}",
     *      operationId="deleteCategory",
     *      tags={"Categories"},
     *      summary="Delete a category",
     *      security={{"bearerAuth":{}}},
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
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->delete()) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
