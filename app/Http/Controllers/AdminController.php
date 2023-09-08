<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserFilterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use App\Http\Resources\UserResource;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/create",
     *      operationId="createAdmin",
     *      tags={"Admin"},
     *      summary="Create an Admin account",
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
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user_resource = $this->userService->registerAdmin($request->validFields());

        $user_resource->token = $this->userService->adminLogin($request->only('email', 'password'));

        return $this->jsonResponse(data:$user_resource);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/login",
     *      operationId="adminLogin",
     *      tags={"Admin"},
     *      summary="Admin Login",
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
     * Admin login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->userService->adminLogin($request->only('email', 'password'));
        if ($token !== null) {
            return $this->jsonResponse(data:['token' => $token]);
        }

        return $this->jsonResponse(status_code: Response::HTTP_UNAUTHORIZED, error:__('auth.failed'));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/logout",
     *      operationId="adminLogout",
     *      tags={"Admin"},
     *      summary="Admin Logout",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     * Logs current user out
     *
     * @return JsonResponse
     */
    public function logout()
    {
        if (Auth::logout()) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(status_code:Response::HTTP_UNPROCESSABLE_ENTITY, error:__('auth.logout_error'));
    }
    
    /**
     * @OA\Post(
     *      path="/api/v1/admin/user-listing",
     *      operationId="UserList",
     *      tags={"Admin"},
     *      summary="Fetch user listing",
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
     *      @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          @OA\Schema(
     *             type="email",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="phone_number",
     *          in="query",
     *          @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="address",
     *          in="query",
     *          @OA\Schema(
     *             type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="created_at",
     *          in="query",
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="is_marketing",
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
     * Get a paginated list of users
     *
     * @param UserFilterRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function userListing(UserFilterRequest $request)
    {
        $filter_params = $request->filterParams();
        $data = UserResource::collection(User::getUsers($filter_params))->resource;

        return $this->jsonResponse(data: $data);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/user-edit/{uuid}",
     *      operationId="UpdateUser",
     *      tags={"Admin"},
     *      summary="Update an existing user",
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
     * Edit user account
     *
     * @param User $user
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function userEdit(User $user, UpdateUserRequest $request)
    {
        if ($user->isAdmin()) {
            return $this->jsonResponse(
                status_code: Response::HTTP_UNPROCESSABLE_ENTITY,
                error: __('profile.admin_edit_disallowed')
            );
        }

        if ($this->userService->update($user, $request->validFields())) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(
            status_code: Response::HTTP_UNPROCESSABLE_ENTITY,
            error: __('profile.edit_failed')
        );
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/user-delete/{uuid}",
     *      operationId="DeleteUser",
     *      tags={"Admin"},
     *      summary="Delete a user",
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
     * Delete user account
     *
     * @param User $user
     * @return JsonResponse
     */
    public function userDelete(User $user)
    {
        if ($user->isAdmin()) {
            return $this->jsonResponse(
                status_code: Response::HTTP_UNPROCESSABLE_ENTITY,
                error: __('profile.admin_delete_disallowed')
            );
        }

        if ($this->userService->delete($user)) {
            return $this->jsonResponse();
        }

        return $this->jsonResponse(status_code: Response::HTTP_UNPROCESSABLE_ENTITY, error: 'profile.delete_failed');
    }
}
