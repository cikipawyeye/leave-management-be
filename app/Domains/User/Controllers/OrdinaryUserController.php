<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Actions\VerifyUserAction;
use App\Domains\User\Constants\PermissionConstant as P;
use App\Domains\User\DataTransferObjects\UserData;
use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserCriteria;
use App\Domains\User\Repositories\UserRepository;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrdinaryUserController extends ApiController
{
    public function __construct()
    {
        $this->middleware(sprintf('permission:%s', P::BROWSE_ORDINARY_USERS))->only('index');
        $this->middleware(sprintf('permission:%s', P::READ_ORDINARY_USER))->only('show');
        $this->middleware(sprintf('permission:%s', P::VERIFY_ORDINARY_USER))->only('verify');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $criteria = UserCriteria::from([...$request->all(), 'role' => RoleEnum::User->value]);
        $repository = new UserRepository($criteria);
        $paginate = $request->boolean('paginate', true);
        $data = ! $paginate
            ? $repository->get()
            : $repository->paginate($request->all());

        return $this->resource(UserData::class, $data, 'role');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $ordinaryUser): JsonResponse
    {
        return $this->sendJsonResponse(
            UserData::fromModel($ordinaryUser)->include('role'),
        );
    }

    /**
     * Display the specified resource.
     */
    public function verify(User $ordinaryUser): JsonResponse
    {
        dispatch_sync(new VerifyUserAction($ordinaryUser));

        return $this->sendJsonResponse(
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
