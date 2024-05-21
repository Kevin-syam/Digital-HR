<?php

namespace App\Resources\Dashboard;

use App\Models\User;
use App\Resources\Attendance\TodayAttendanceResource;
use App\Resources\Attendance\WeeklyAttendanceReportCollection;
use App\Resources\Attendance\WeeklyAttendanceTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReportResource extends JsonResource
{
    public function toArray($request)
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'avatar' => ($this->avatar) ? asset(User::AVATAR_UPLOAD_PATH . $this->avatar) : asset('assets/images/img.png'),
            'online_status' => ($this->online_status==1),
        ];
    }
}













