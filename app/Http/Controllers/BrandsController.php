<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Brand;
use Illuminate\Support\Str;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\BrandResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BrandsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/brands",
     *      operationId="brandListing",
     *      tags={"Brands"},
     *      summary="List of brands",
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
     *
     * @throws Exception
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $filter_params = $request->filterParams();
        $data = BrandResource::collection(Brand::getAll($filter_params))->resource;

        return $this->jsonResponse(data:$data);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/brand/create",
     *      operationId="CreateBrand",
     *      tags={"Brands"},
     *      summary="Create a new brand",
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
     * Store a newly created brand in storage.
     */
    public function store(BrandRequest $request): JsonResponse
    {
        $inputs = $request->validFields();
        $inputs['slug'] = Str::slug(strval($request->title));

        $brand = Brand::create($inputs);

        return $this->jsonResponse(data:new BrandResource($brand));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/brand/{uuid}",
     *      operationId="fetchBrand",
     *      tags={"Brands"},
     *      summary="Fetch Brand",
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
    public function show(Brand $brand): JsonResponse
    {
        return $this->jsonResponse(data: new BrandResource($brand));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/brand/{uuid}",
     *      operationId="UpdateBrand",
     *      tags={"Brands"},
     *      summary="Update an existing brand",
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
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $inputs = $request->validFields();
        $inputs['slug'] = Str::slug(strval($request->title));

        if ($brand->update($inputs)) {
            return $this->jsonResponse(data: new BrandResource($brand));
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/brand/{uuid}",
     *      operationId="deleteBrand",
     *      tags={"Brands"},
     *      summary="Delete an existing brand",
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
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): JsonResponse
    {
        if ($brand->delete()) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
