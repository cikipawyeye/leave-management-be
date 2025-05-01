<?php

declare(strict_types=1);

namespace App\Domains\Permit\Controllers;

use App\Domains\Permit\Actions\SavePermitAction;
use App\Domains\Permit\DataTransferObjects\PermitData;
use App\Domains\Permit\Exceptions\PermitException;
use App\Domains\Permit\Models\Permit;
use App\Domains\Permit\Repositories\PermitCriteria;
use App\Domains\Permit\Repositories\PermitRepository;
use App\Domains\Permit\Requests\SavePermitRequest;
use App\Domains\Permit\State\Permit\Canceled;
use App\Domains\User\Constants\PermissionConstant as P;
use App\Domains\User\Enums\RoleEnum;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermitController extends ApiController
{
    public function __construct()
    {
        $this->middleware(sprintf('permission:%s', P::BROWSE_PERMITS))->only('index');
        $this->middleware(sprintf('permission:%s', P::READ_PERMIT))->only('show');
        $this->middleware(sprintf('permission:%s', P::ADD_PERMIT))->only('store');
        $this->middleware(sprintf('permission:%s', P::EDIT_PERMIT))->only('update');
        $this->middleware(sprintf('permission:%s', P::DELETE_PERMIT))->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $criteria = PermitCriteria::from([
            ...$request->all(),
            'user' => $request->user()->hasRole(RoleEnum::User->value)
                ? $request->user()->id
                : $request->input('user'),
        ]);
        $repository = new PermitRepository($criteria);
        $paginate = $request->boolean('paginate', true);
        $data = ! $paginate
            ? $repository->get()
            : $repository->paginate($request->all());

        return $this->resource(PermitData::class, $data, 'user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePermitRequest $request)
    {
        $permit = dispatch_sync(
            new SavePermitAction(
                new Permit,
                PermitData::from($request->validated()),
                $request->user()
            )
        );

        return $this->sendJsonResponse(
            PermitData::fromModel($permit),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Permit $permit)
    {
        if ($request->user()->hasRole(RoleEnum::User->value) && $permit->user_id !== $request->user()->id) {
            abort(404);
        }

        return $this->sendJsonResponse(
            PermitData::fromModel($permit)->include('user'),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SavePermitRequest $request, Permit $permit)
    {
        dispatch_sync(new SavePermitAction(
            $permit,
            PermitData::from($request->validated()),
            $request->user()
        ));

        return $this->sendJsonResponse(
            status: JsonResponse::HTTP_NO_CONTENT,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permit $permit): JsonResponse
    {
        if ($request->user()->id != $permit->user_id) {
            throw PermitException::notAllowedToDelete();
        }

        if ($permit->isProcessed()) {
            throw PermitException::permitAlreadyProcessed();
        }

        $permit->delete();

        return $this->sendJsonResponse(
            status: JsonResponse::HTTP_NO_CONTENT,
        );
    }

    public function cancel(Request $request, Permit $permit): JsonResponse
    {
        if ($request->user()->id != $permit->user_id) {
            throw PermitException::notAllowedToModify();
        }

        if ($permit->isProcessed()) {
            throw PermitException::permitAlreadyProcessed();
        }

        $permit->state->transitionTo(Canceled::class);

        return $this->sendJsonResponse(
            status: JsonResponse::HTTP_NO_CONTENT,
        );
    }
}
