<?php

declare(strict_types=1);

namespace App\Domains\Permit\Controllers;

use App\Domains\Permit\Actions\StorePermitReviewAction;
use App\Domains\Permit\DataTransferObjects\PermitReviewData;
use App\Domains\Permit\Models\PermitReview;
use App\Domains\Permit\Repositories\PermitReviewCriteria;
use App\Domains\Permit\Repositories\PermitReviewRepository;
use App\Domains\Permit\Requests\StorePermitReviewRequest;
use App\Domains\User\Constants\PermissionConstant as P;
use App\Support\Controllers\ApiController;
use Illuminate\Http\Request;

class PermitReviewController extends ApiController
{
    public function __construct()
    {
        $this->middleware(sprintf('permission:%s', P::BROWSE_PERMIT_REVIEWS))->only('index');
        $this->middleware(sprintf('permission:%s', P::READ_PERMIT_REVIEW))->only('show');
        $this->middleware(sprintf('permission:%s', P::ADD_PERMIT_REVIEW))->only('store');
        $this->middleware(sprintf('permission:%s', P::EDIT_PERMIT_REVIEW))->only('update');
        $this->middleware(sprintf('permission:%s', P::DELETE_PERMIT_REVIEW))->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $criteria = PermitReviewCriteria::from($request->all());
        $repository = new PermitReviewRepository($criteria);
        $paginate = $request->boolean('paginate', true);
        $data = ! $paginate
            ? $repository->get()
            : $repository->paginate($request->all());

        return $this->resource(PermitReviewData::class, $data, 'reviewer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermitReviewRequest $request)
    {
        $permit = dispatch_sync(
            new StorePermitReviewAction(
                new PermitReview,
                PermitReviewData::from($request->validated()),
                $request->user()
            )
        );

        return $this->sendJsonResponse(
            PermitReviewData::fromModel($permit),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitReview $permitReview)
    {
        return $this->sendJsonResponse(
            PermitReviewData::fromModel($permitReview)->include('reviewer', 'permit'),
        );
    }
}
