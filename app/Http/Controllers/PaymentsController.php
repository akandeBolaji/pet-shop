<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/payments",
     *      operationId="ListPayments",
     *      tags={"Payments"},
     *      summary="Fetch payments",
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
        $data = PaymentResource::collection(Payment::getAll($filter_params))->resource;

        return $this->jsonResponse(data:$data);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/payment/create",
     *      operationId="CreateAPayment",
     *      tags={"Payments"},
     *      summary="Create a new payment",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "type",
     *                      "details",
     *                  },
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      enum={"credit_card", "bank_transfer", "cash_on_delivery"}
     *                  ),
     *                  @OA\Property(property="details", type="object"),
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
    public function store(PaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validFields());

        return $this->jsonResponse(data: $payment->uuid);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/payment/{uuid}",
     *      operationId="Fetch Payment",
     *      tags={"Payments"},
     *      summary="Fetch a payment",
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
     * Display the specified resource.
     */
    public function show(Payment $payment): JsonResponse
    {
        return $this->jsonResponse(data: new PaymentResource($payment));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/payment/{uuid}",
     *      operationId="UpdatePayment",
     *      tags={"Payments"},
     *      summary="Update an existing payment",
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
     *                      "type",
     *                      "details",
     *                  },
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      enum={"credit_card", "bank_transfer", "cash_on_delivery"}
     *                  ),
     *                  @OA\Property(property="details", type="object"),
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
    public function update(PaymentRequest $request, Payment $payment): JsonResponse
    {
        if ($payment->update($request->validFields())) {
            return $this->jsonResponse(data: new PaymentResource($payment));
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/payment/{uuid}",
     *      operationId="DeletePayment",
     *      tags={"Payments"},
     *      summary="Delete a payment",
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
    public function destroy(Payment $payment): JsonResponse
    {
        if ($payment->delete()) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
