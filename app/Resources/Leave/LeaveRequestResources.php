<?php

namespace App\Resources\Leave;

use App\Helpers\AppHelper;
use App\Models\AppSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResources extends JsonResource
{
    public function toArray($request)
    {
        $slug = '24-hour-format';
        $appTimeSetting = AppSetting::where('slug',$slug)->first();

        return [
            'id' => $this->id,
            'no_of_days' => $this->no_of_days,
            'leave_type_id' => $this->leave_type_id,
            'leave_type_name' => ucfirst($this->leaveType->name),
            'leave_from' => AppHelper::convertLeaveDateFormat($this->leave_from,false),
            'leave_to' => AppHelper::convertLeaveDateFormat($this->leave_to,false),
            'leave_from_nepali' => AppHelper::convertLeaveDateFormat($this->leave_from,true),
            'leave_to_nepali' => AppHelper::convertLeaveDateFormat($this->leave_to,true),
            'leave_requested_date' => AppHelper::convertLeaveDateFormat($this->leave_requested_date),
            'status' => ucfirst($this->status),
            'leave_reason' => $this->reasons,
            'admin_remark' => $this->admin_remark ?? '-',
            'early_exit' => ($this->early_exit==1),
            'status_updated_by' => ($this->leaveRequestUpdatedBy) ? $this->leaveRequestUpdatedBy->name : '',

        ];
    }
}













