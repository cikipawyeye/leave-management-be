<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Actions\ResetUserPasswordAction;
use App\Domains\User\Actions\SetUserRoleAction;
use App\Domains\User\Actions\StoreUserAction;
use App\Domains\User\Constants\PermissionConstant as Permission;
use App\Domains\User\DataTransferObjects\UserData;
use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserCriteria;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\User\Requests\ResetUserPasswordRequest;
use App\Domains\User\Requests\SaveUserRequest;
use App\Domains\User\Requests\SetUserRoleRequest;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware(sprintf('permission:%s', Permission::BROWSE_USERS))->only('index');
        $this->middleware(sprintf('permission:%s', Permission::READ_USER))->only('show');
        $this->middleware(sprintf('permission:%s', Permission::ADD_USER))->only('store');
        $this->middleware(sprintf('permission:%s', Permission::EDIT_USER))->only('update', 'resetPassword');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $criteria = UserCriteria::from($request->all());
        $repository = new UserRepository($criteria);
        $paginate = $request->boolean('paginate');
        $data = ! $paginate
            ? $repository->get()
            : $repository->paginate($request->all());

        return $this->resource(UserData::class, $data, 'role');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveUserRequest $request): JsonResponse
    {
        $user = dispatch_sync(new StoreUserAction(
            UserData::from($request->validated()),
            $request->boolean('should_verify_email')
        ));

        return $this->sendJsonResponse(
            UserData::fromModel($user)->include('role'),
            message: __('app.stored_data', ['data' => __('app.user')]),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return $this->sendJsonResponse(
            UserData::fromModel($user)->include('role'),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SetUserRoleRequest $request, User $user): JsonResponse
    {
        dispatch_sync(new SetUserRoleAction($user, RoleEnum::fromValue($request->input('role'))));

        return $this->sendJsonResponse(
            status: Response::HTTP_NO_CONTENT
        );
    }

    public function resetPassword(ResetUserPasswordRequest $request, User $user): JsonResponse
    {
        dispatch_sync(new ResetUserPasswordAction($user, $request->input('password')));

        return $this->sendJsonResponse(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
