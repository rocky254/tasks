<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $users = User::all();
            return $this->successResponse(UserResource::collection($users), 'Users Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in UserController.index', $e->getMessage(), 500);
        }
    }

    /**
     * Get All Users Count
     */
    public function allUsersCount()
    {
        try
        {
            $users = User::all();
            return $this->successResponse(count($users), 'Users Count Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in UserController.allUsersCount', $e->getMessage(), 500);
        }
    }

}
