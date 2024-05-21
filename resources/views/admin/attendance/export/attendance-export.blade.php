<table>
    <thead>
    <tr>
        <th colspan="5" style="text-align: center"><strong>{{ ucfirst($employeeDetail->name)}}
                @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                    {{\App\Helpers\AppHelper::MONTHS[date("n", strtotime($attendanceRecordDetail[0]['attendance_date']))]['np']}}
                @else
                    {{date("F", strtotime($attendanceRecordDetail[0]['attendance_date']))}}
                @endif
                Attendance Report  </strong></th>
    </tr>
    <tr>
        <th><b>Date</b></th>
        <th style="text-align: center;"><b>Check In At</b></th>
        <th style="text-align: center;"><b>Check Out At</b></th>
        <th style="text-align: center;"><b>Total Worked Hours</b></th>
        <th style="text-align: center;"><b>Attendance Status</b></th>
    </tr>
    </thead>
    <tbody>
    @forelse($attendanceRecordDetail as $key =>$datum)
        <tr>
            <td>{{$datum['attendance_date']}} ({{ \App\Helpers\AppHelper::weekDay($datum['attendance_date'])}})</td>
            @if(isset($datum['check_in_at']))
                <td align="center">{{ ($datum['check_in_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($datum['check_in_at']):''}}</td>
                <td align="center">{{ ($datum['check_out_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($datum['check_out_at']):''}}</td>
                <td align="center">
                    @if($datum['check_out_at'])
                        {{\App\Helpers\AttendanceHelper::getWorkedHourInHourAndMinute($datum['check_in_at'],$datum['check_out_at'])}}
                    @endif
                </td>
                <td align="center">{{($datum['attendance_status']) ? 'Approved' : 'Rejected' }}</td>
            @else
                <td align="center">x</td>
                <td align="center">x</td>
                <td align="center">x</td>
                <?php
                    $reason = (\App\Helpers\AttendanceHelper::getHolidayOrLeaveDetail($datum['attendance_date'], $employeeDetail->id));
                ?>
                @if($reason)
                    <td align="center">
                        <span class="btn btn-outline-secondary btn-xs">
                            {{$reason}}
                        </span>
                    </td>
                @else
                    <td align="center">x</td>
                @endif
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
