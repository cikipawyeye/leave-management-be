<?php

declare(strict_types=1);

namespace App\Domains\User\Constants;

use App\Support\Constants\Constant;

class PermissionConstant extends Constant
{
    public const VIEW_HORIZON_DASHBOARD = 'view_horizon_dashboard';

    // All User BREAD
    public const MANAGE_USERS = 'manage_users';
    public const BROWSE_USERS = 'browse_users';
    public const READ_USER = 'read_user';
    public const EDIT_USER = 'edit_user';
    public const ADD_USER = 'add_user';
    public const DELETE_USER = 'delete_user';

    // Ordinary User BREAD
    public const MANAGE_ORDINARY_USERS = 'manage_ordinary_users';
    public const BROWSE_ORDINARY_USERS = 'browse_ordinary_users';
    public const READ_ORDINARY_USER = 'read_ordinary_user';
    public const VERIFY_ORDINARY_USER = 'verify_ordinary_user';

    // Permit BREAD
    public const MANAGE_PERMITS = 'manage_permits';
    public const BROWSE_PERMITS = 'browse_permits';
    public const READ_PERMIT = 'read_permit';
    public const EDIT_PERMIT = 'edit_permit';
    public const ADD_PERMIT = 'add_permit';
    public const DELETE_PERMIT = 'delete_permit';
    public const SET_PERMIT_STATE_PENDING = 'set_permit_state_pending';
    public const SET_PERMIT_STATE_REVISION = 'set_permit_state_revision';
    public const SET_PERMIT_STATE_APPROVED = 'set_permit_state_approved';
    public const SET_PERMIT_STATE_REJECTED = 'set_permit_state_rejected';
    public const SET_PERMIT_STATE_CANCELED = 'set_permit_state_canceled';

    // Permit Review BREAD
    public const MANAGE_PERMIT_REVIEWS = 'manage_permit_reviews';
    public const BROWSE_PERMIT_REVIEWS = 'browse_permit_reviews';
    public const READ_PERMIT_REVIEW = 'read_permit_review';
    public const EDIT_PERMIT_REVIEW = 'edit_permit_review';
    public const ADD_PERMIT_REVIEW = 'add_permit_review';
    public const DELETE_PERMIT_REVIEW = 'delete_permit_review';
}
