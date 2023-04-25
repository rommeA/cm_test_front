<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="List",
     *     description="Lists all users",
     *     operationId="usersList",
     *     tags={"user"},
     *     @OA\Parameter(
     *        description="Items per page",
     *        in="query",
     *        name="perPage",
     *        required=false,
     *        example="15",
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *                     ),
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('perPage') ?? 15;
        $users = User::paginate($perPage);
        return (new UserCollection($users))->response();
    }


    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Show",
     *     description="Get user by slug or id",
     *     operationId="usersShow",
     *     tags={"user"},
     *     @OA\Parameter(
     *        description="User slug or id",
     *        in="path",
     *        name="user",
     *        required=true,
     *        example="john_doe",
     *        @OA\Schema(
     *           type="string",
     *        )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     * )
     */
    public function show(Request $request, User $user)
    {
        return (new UserResource($user))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/users/{user}",
     *     summary="Update",
     *     description="Update user",
     *     operationId="usersUpdate",
     *     tags={"user"},
     *     @OA\Parameter(
     *        description="Update user",
     *        in="path",
     *        name="user",
     *        required=true,
     *        example="john_doe",
     *        @OA\Schema(
     *           type="string",
     *        )
     *     ),
     * @OA\RequestBody(
     *    @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *          @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *          @OA\Property(property="firstname", type="string", maxLength=32, example="John"),
     *          @OA\Property(property="lastname", type="string", maxLength=32, example="John"),
     *          @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          @OA\Property(property="is_seaman", type="bool", example="1"),
     *          @OA\Property(property="is_office_employee", type="bool", example="0"),
     *          @OA\Property(property="position", type="string"),
     *      )
     *    ),
     *
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     * )
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|max:255|email|unique:users,email,'.$user->id,
            'password' => 'regex:/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
            'lastname' => 'string|max:255',
            'firstname' => 'string|max:255',
            'is_seaman' => 'bool',
            'is_office_employee' => 'bool',
            'position' => 'string|max:255',
        ]);

        $data = $request->toArray();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        \Log::info("User ID {$user->id} updated successfully.");

        return (new UserResource($user))->response();
    }


    /**
     * @OA\Post(
     * path="/api/users",
     * summary="Create",
     * description="Create user",
     * operationId="createUser",
     * tags={"user"},
     * @OA\RequestBody(
     *    @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *          @OA\Property(property="firstname", type="string", maxLength=32, example="John"),
     *          @OA\Property(property="lastname", type="string", maxLength=32, example="John"),
     *          @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          @OA\Property(property="is_seaman", type="bool", example="1"),
     *          @OA\Property(property="is_office_employee", type="bool", example="0"),
     *          @OA\Property(property="position", type="string"),
     *      )
     *    ),
     *
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Success")
     *        )
     *     )
     * ),
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge(['is_seaman' => $request->get('is_seaman') === 'true']);
        $request->merge(['is_office_employee' => $request->get('is_office_employee')  === 'true']);

        $request->validate([
            'email' => 'bail|required|string|max:255|email|unique:users,email',
            'password' => 'bail|required|string|min:8',
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'is_seaman' => 'boolean',
            'is_office_employee' => 'boolean',
            'position' => 'string|max:255',

        ]);


        $data = $request->toArray();
        $data['password'] = Hash::make($request->input('password'));

        $user = new User($data);

        $user->email = $request->input('email');
        $user->password = $data['password'];
        $user->lastname = $request->input('lastname');
        $user->firstname = $request->input('firstname');
        $user->is_seaman = $request->input('is_seaman');
        $user->is_office_employee = $request->input('is_office_employee');
        $user->position = $request->input('position');

        $user->save();

        \Log::info("User ID {$user->id} created successfully.");

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_CREATED);
    }


    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     summary="Delete",
     *     description="Delete user",
     *     operationId="usersDelete",
     *     tags={"user"},
     *     @OA\Parameter(
     *        description="Delete user",
     *        in="path",
     *        name="user",
     *        required=true,
     *        example="john_doe",
     *        @OA\Schema(
     *           type="string",
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *       @OA\Property(property="message", type="string", example="Success")
     *     )
     * )
     */
    public function destroy(User $user): Response
    {
        $user->delete();

        \Log::info("User ID {$user->id} deleted successfully.");

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
