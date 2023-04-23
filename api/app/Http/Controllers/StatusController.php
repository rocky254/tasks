<?php

namespace App\Http\Controllers;

use App\Http\Requests\Status\StoreStatusRequest;
use App\Http\Requests\Status\UpdateStatusRequest;
use App\Http\Resources\StatusResource;
use App\Models\Status;
use Exception;

class StatusController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $resource = Status::all();
            return $this->successResponse(StatusResource::collection($resource), 'Items Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in StatusController.index', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        try
        {
            $status = new Status();
            $status->name = $request->name;
            $status->save();

            return $this->successResponse(new StatusResource($status), 'Status Saved Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in StatusController.store', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        try
        {
            return $this->successResponse(new StatusResource($status), 'Status Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in StatusController.show', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {
        try
        {
            $response = $status->update($request->all());
            return $this->successResponse(new StatusResource($status), 'Status Updated Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in StatusController.update', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        try
        {
            $response = $status->delete();
            return $this->successResponse(new StatusResource($status), 'Status Deleted Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in StatusController.destroy', $e->getMessage(), 500);
        }
    }
}
