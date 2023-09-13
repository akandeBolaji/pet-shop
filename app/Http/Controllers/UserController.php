<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/create",
     *      operationId="createUser",
     *      tags={"User"},
     *      summary="Create an User account",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name",
     *                      "last_name",
     *                      "email",
     *                      "phone_number",
     *                      "address",
     *                      "password",
     *                      "password_confirmation",
     *                  },
     *                  @OA\Property(property="first_name", type="string"),
     *                  @OA\Property(property="last_name", type="string"),
     *                  @OA\Property(property="email", type="email"),
     *                  @OA\Property(property="phone_number", type="string"),
     *                  @OA\Property(property="address", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *                  @OA\Property(property="password_confirmation", type="string"),
     *                  @OA\Property(property="avatar", type="string"),
     *                  @OA\Property(property="is_marketing", type="string"),
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
     * Register new user and return user data
     *
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user_resource = $this->userService->registerUser($request->validFields());

        $user_resource->token = $this->userService->userLogin($request->only('email', 'password'));

        return $this->jsonResponse(data: $user_resource);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/login",
     *      operationId="userLogin",
     *      tags={"User"},
     *      summary="User Login",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "email",
     *                      "password",
     *                  },
     *                  @OA\Property(property="email", type="email"),
     *                  @OA\Property(property="password", type="string")
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
     * User login
     *
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->userService->userLogin($request->only('email', 'password'));
        if ($token !== null) {
            return $this->jsonResponse(data: ['token' => $token]);
        }

        return $this->jsonResponse(status_code: Response::HTTP_UNAUTHORIZED, error: __('auth.failed'));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user/logout",
     *      operationId="userLogout",
     *      tags={"User"},
     *      summary="User Logout",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Logs current user out
     */
    public function logout(): JsonResponse
    {
        if (Auth::attemptLogout()) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(status_code: Response::HTTP_UNPROCESSABLE_ENTITY, error: __('auth.logout_error'));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user",
     *      operationId="ViewUser",
     *      tags={"User"},
     *      summary="Fetch user",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Returns user info
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->jsonResponse(data: new UserResource($request->user()));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/user/edit",
     *      operationId="editUser",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      summary="Edit User account",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name",
     *                      "last_name",
     *                      "email",
     *                      "phone_number",
     *                      "address",
     *                      "password",
     *                      "password_confirmation",
     *                  },
     *                  @OA\Property(property="first_name", type="string"),
     *                  @OA\Property(property="last_name", type="string"),
     *                  @OA\Property(property="email", type="email"),
     *                  @OA\Property(property="phone_number", type="string"),
     *                  @OA\Property(property="address", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *                  @OA\Property(property="password_confirmation", type="string"),
     *                  @OA\Property(property="avatar", type="string"),
     *                  @OA\Property(property="is_marketing", type="string"),
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
     * Updates user record
     *
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();

        Gate::denyIf(fn ($user) => $user->isAdmin());

        if ($user !== null && $this->userService->update($user, $request->all())) {
            return $this->jsonResponse();
        }

        return response()->json(['success' => 0, 'error' => __('profile.edit_failed')]);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/user",
     *      operationId="deleteUserAccount",
     *      tags={"User"},
     *      summary="Delete a User Account",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Delete user record
     *
     * @throws AuthorizationException
     */
    public function delete(Request $request): JsonResponse
    {
        $user = $request->user();

        Gate::denyIf(fn ($user) => $user->isAdmin());

        if ($user !== null && $this->userService->delete($user)) {
            return $this->jsonResponse();
        }

        return response()->json(['success' => 0, 'error' => __('profile.delete_failed')]);
    }
}
