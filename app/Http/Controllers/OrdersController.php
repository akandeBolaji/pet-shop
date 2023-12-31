<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Http\Services\OrderService;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/api/v1/orders",
     *      operationId="ListOrders",
     *      tags={"Orders"},
     *      summary="Fetch orders",
     *      security={{"bearerAuth":{}}},
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
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $filter_params = $request->filterParams();

        return $this->jsonResponse(data:$this->orderService->getAll($filter_params));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/orders/dashboard",
     *      operationId="OrdersDashboard",
     *      tags={"Orders"},
     *      summary="Fetch orders for dashboard",
     *      security={{"bearerAuth":{}}},
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
     * Display a listing of the resource.
     */
    public function dashboad(FilterRequest $request): JsonResponse
    {
        $filter_params = $request->filterParams();

        return $this->jsonResponse(data:$this->orderService->getAll($filter_params));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/order/create",
     *      operationId="CreateOrder",
     *      tags={"Orders"},
     *      summary="Create a new order",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "order_status_uuid",
     *                      "products",
     *                      "payment_uuid",
     *                      "address",
     *                  },
     *                  @OA\Property(property="order_status_uuid", type="string"),
     *                  @OA\Property(
     *                   property="products",
     *                   type="array",
     *                   @OA\Items(
     *                        type="object",
     *                     )
     *                  ),
     *                  @OA\Property(property="payment_uuid", type="string"),
     *                  @OA\Property(property="address", type="object"),
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
    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->validFields());
        if ($order !== null) {
            return $this->jsonResponse(data: new OrderResource($order));
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY, error: __('orders.creation_failed'));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/order/{uuid}",
     *      operationId="FetchAnOrder",
     *      tags={"Orders"},
     *      summary="Fetch an order",
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
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->jsonResponse(data: new OrderResource($order));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/order/{uuid}",
     *      operationId="UpdateOrder",
     *      tags={"Orders"},
     *      summary="Update an existing order",
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
     *                      "order_status_uuid",
     *                      "products",
     *                      "payment_uuid",
     *                      "address",
     *                  },
     *                  @OA\Property(property="order_status_uuid", type="string"),
     *                  @OA\Property(
     *                   property="products",
     *                   type="array",
     *                   @OA\Items(
     *                        type="object",
     *                     )
     *                  ),
     *                  @OA\Property(property="payment_uuid", type="string"),
     *                  @OA\Property(property="address", type="object"),
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
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        if ($this->orderService->update($order, $request->validFields())) {
            return $this->jsonResponse(data: new OrderResource($order));
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY, error: __('orders.update_failed'));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/order/{uuid}",
     *      operationId="DeleteAnOrder",
     *      tags={"Orders"},
     *      summary="Delete an order",
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
     * Remove the specified order from storage.
     *
     * @throws Throwable
     */
    public function destroy(Order $order): JsonResponse
    {
        if ($this->orderService->delete($order)) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
