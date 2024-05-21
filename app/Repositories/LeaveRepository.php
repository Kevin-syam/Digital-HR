<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Models\LeaveRequestMaster;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveRepository
{
    public function getAllEmployeeLeaveRequest($filterParameters, $select = ['*'], $with = [])
    {
        $leaveDetailList = LeaveRequestMaster::with($with)
            ->select($select)
            ->whereHas('leaveRequestedBy', function ($query) use ($filterParameters) {
                $query->where('name', 'like', '%' . $filterParameters['requested_by'] . '%')
                    ->whereNull('deleted_at');
            })
            ->when(isset($filterParameters['leave_type']), function ($query) use ($filterParameters) {
                $query->where('leave_type_id', $filterParameters['leave_type']);
            })
            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            });
        if (isset($filterParameters['start_date'])) {
            $leaveDetailList
                ->whereBetween('leave_requested_date', [$filterParameters['start_date'], $filterParameters['end_date']]);
        } else {
            $leaveDetailList
                ->when(isset($filterParameters['month']), function ($query) use ($filterParameters) {
                    $query->whereMonth('leave_requested_date', '=', $filterParameters['month']);
                })
                ->when(isset($filterParameters['year']), function ($query) use ($filterParameters) {
                    $query->whereYear('leave_requested_date', '=', $filterParameters['year']);
                });
        }
        return $leaveDetailList
            ->orderBy('id', 'DESC')
            ->paginate(LeaveRequestMaster::RECORDS_PER_PAGE);
    }

    public function getAllLeaveRequestDetailOfEmployee($filterParameters, $select = ['*'], $with = [])
    {
        $leaveDetailList = LeaveRequestMaster::with($with)->select($select)
            ->when(isset($filterParameters['leave_type']), function ($query) use ($filterParameters) {
                $query->where('leave_type_id', $filterParameters['leave_type']);
            })
            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })
            ->when(isset($filterParameters['early_exit']), function ($query) use ($filterParameters) {
                $query->where('early_exit', $filterParameters['early_exit']);
            })
            ->where('requested_by', $filterParameters['user_id']);
        if (isset($filterParameters['start_date'])) {
            $leaveDetailList->whereBetween('leave_requested_date', [$filterParameters['start_date'], $filterParameters['end_date']]);
        } else {
            $leaveDetailList
                ->when(isset($filterParameters['month']), function ($query) use ($filterParameters) {
                    $query->whereMonth('leave_from', '=', $filterParameters['month']);
                })
                ->whereYear('leave_from', '=', $filterParameters['year']);
        }
        return $leaveDetailList->orderBy('id', 'DESC')
            ->get();
    }

    public function findEmployeeLeaveRequestByEmployeeId($leaveRequestId, $select = ['*'], $with = [])
    {
        return LeaveRequestMaster::with($with)
            ->select($select)
            ->where('id', $leaveRequestId)
            ->first();
    }

    public function employeeTotalApprovedLeavesForGivenLeaveType($leaveType, $select = ['*'])
    {
        return LeaveRequestMaster::select(DB::raw("SUM(no_of_days) as leaves"))
            ->where('requested_by', getAuthUserCode())
            ->where('status', 'approved')
            ->where('leave_type_id', $leaveType)
            ->groupBy('leave_type_id')
            ->first();
    }

    public function getEmployeeLatestLeaveRequestBetweenFromAndToDate($select = ['*'], $date)
    {
        return LeaveRequestMaster::query()
            ->select($select)
            ->where(function ($query) use ($date) {
                $query->whereBetween('leave_from', [$date['from_date'], $date['to_date']])
                    ->orWhereBetween('leave_to', [$date['from_date'], $date['to_date']]);
            })
            ->whereIn('status', ['pending', 'approved'])
            ->where('requested_by', getAuthUserCode())
            ->first();
    }

    public function store($validatedData)
    {
        return LeaveRequestMaster::create($validatedData)->fresh();
    }

    public function update($leaveRequestDetail, $validatedData)
    {
        return $leaveRequestDetail->update($validatedData);
    }

    public function findLeaveRequestCountByLeaveTypeId($leaveTypeId)
    {
        return LeaveRequestMaster::select('id')->where('leave_type_id', $leaveTypeId)->count();
    }

    public function getLeaveCountDetailOfEmployeeOfTwoMonth()
    {
        $date['current_month'] = Carbon::now()->startOfMonth();
        $date['next_month'] = Carbon::now()->startOfMonth()->addMonth(1);
        return LeaveRequestMaster::select('no_of_days', 'leave_from')
            ->whereHas('leaveRequestedBy', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('status', 'approved')
            ->where(function ($query) use ($date) {
                $query->whereBetween('leave_from', [$date['current_month'], $date['next_month']])
                    ->orWhereBetween('leave_to', [$date['current_month'], $date['next_month']]);
            })
            ->orderBy('leave_from')
            ->get();
    }

    public function getAllEmployeeLeaveDetailBySpecificDay($filterParameter)
    {
        $date['current_month'] = Carbon::now()->startOfMonth();
        $date['upcoming_month'] = Carbon::now()->startOfMonth()->addMonth(1);
        return LeaveRequestMaster::select(
            'leave_requests_master.id as leave_id',
            'users.id as user_id',
            'users.name as name',
            'users.avatar as avatar',
            'departments.dept_name as department',
            'posts.post_name as post',
            'leave_requests_master.no_of_days as no_of_days',
            'leave_requests_master.leave_from as leave_from',
            'leave_requests_master.leave_to as leave_to',
            'leave_requests_master.status as leave_status'
        )
            ->Join('users', function ($join) {
                $join->on('leave_requests_master.requested_by', '=', 'users.id')
                    ->whereNUll('users.deleted_at');
            })
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->join('posts', 'posts.id', '=', 'users.post_id')

            ->where(function ($query) use ($date) {
                $query->whereBetween('leave_requests_master.leave_from', [$date['current_month'], $date['upcoming_month']])
                    ->orWhereBetween('leave_requests_master.leave_to', [$date['current_month'], $date['upcoming_month']]);
            })

            ->where(function ($query) use ($filterParameter) {
                $query->whereDate('leave_requests_master.leave_from', '<=', $filterParameter['leave_date'])
                    ->whereDate('leave_requests_master.leave_to', '>=', $filterParameter['leave_date']);
            })

            ->where('leave_requests_master.status', 'approved')
            ->orderBy('leave_requests_master.leave_from')
            ->get();
    }

    public function findEmployeeApprovedLeaveForCurrentDate($filterData, $select = ['*'])
    {
        return LeaveRequestMaster::select($select)
            ->whereDate('leave_from', '<=', AppHelper::getCurrentDateInYmdFormat())
            ->whereDate('leave_to', '>=', AppHelper::getCurrentDateInYmdFormat())
            ->whereIn('status', ['approved','pending'])
            ->where('company_id', $filterData['company_id'])
            ->where('requested_by', $filterData['user_id'])
            ->first();
    }

    public function findEmployeeLeaveRequestDetailById($leaveRequestId,$employeeId,$select=['*'])
    {
        return LeaveRequestMaster::query()
            ->select($select)
            ->where('id', $leaveRequestId)
            ->where('requested_by', $employeeId)
            ->first();
    }


}
