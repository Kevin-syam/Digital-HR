<table>
    <thead>
    <tr>
        <th colspan="5" style="text-align: center"><strong>
                @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                    {{\App\Helpers\AppHelper::getFormattedNepaliDate($dayDetail['attendance_date'])}}
                @else
                {{ date('M d Y',strtotime($dayDetail['attendance_date']))}}
                @endif
                Attendance Report  </strong></th>
    </tr>
    <tr>
        <th><b>Employee Name</b></th>
        <th style="text-align: center;"><b>Check In At</b></th>
        <th style="text-align: center;"><b>Check Out At</b></th>
        <th style="text-align: center;"><b>Total Worked Hours</b></th>
        <th style="text-align: center;"><b>Attendance Status</b></th>
    </tr>
    </thead>
    <tbody>
    @forelse($attendanceDayWiseRecord as $key =>$value)
        <tr>
            <td>{{ucfirst($value->user_name)}}</td>
            @if(isset($value['check_in_at']))
                <td align="center">{{ ($value['check_in_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_in_at']):''}}</td>
                <td align="center">{{ ($value['check_out_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_out_at']):''}}</td>
                <td align="center">
                    @if($value['check_out_at'])
                        {{\App\Helpers\AttendanceHelper::getWorkedHourInHourAndMinute($value['check_in_at'],$value['check_out_at'])}}
                    @endif
                </td>
                <td align="center">{{($value['attendance_status']) ? 'Approved' : 'Rejected' }}</td>
            @else
                <td align="center">x</td>
                <td align="center">x</td>
                <td align="center">x</td>
                <td align="center">x</td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="100%">
                <p class="text-center"><b>No records found!</b></p>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
