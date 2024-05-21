
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#checkIn').click(function (event) {
        event.preventDefault();
        let href = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure you want to Check In Employee ?',
            showDenyButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
            padding:'10px 50px 10px 50px',
            // width:'1000px',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        })
    })

        $('#checkOut').click(function (event) {
            event.preventDefault();
            let href = $(this).attr('href');;
            Swal.fire({
                title: 'Are you sure you want to Check Out Employee ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                // width:'1000px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        $('.changeAttendanceStatus').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to change Attendance Status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                // width:'1000px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        $('.checkLocation').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            $('.attendancelocation').attr("src", href);
        })

        $('body').on('click', '.editAttendance', function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            let attendanceIn = $(this).data('in');
            let attendanceOut = $(this).data('out');
            let editRemark = $(this).data('remark');
            let date = $(this).data('date');
            let name = $(this).data('name');

            $('.modal-title').html('Edit Attendance('+name+') Time of '+date);
            $('#editAttendance').attr('action',url)
            $('#check_in').val(attendanceIn)
            $('#check_out').val(attendanceOut)
            $('#remark').val(editRemark)
            $('#attendanceForm').modal('show');
        });

        function getAttendanceFilterParam()
        {
            let params = {
                year: $('#year').val(),
                month: $('#month').val()
            }
            return params;
        }

        function getDayWiseAttendanceFilterParam()
        {
            let params = {
                attendance_date: $('#attendance_date').val(),
            }
            return params;
        }

        $('#download-excel').on('click',function (e){
            e.preventDefault();
            let route = $(this).data('href');
            let filterd_params = getAttendanceFilterParam();
            filterd_params.download_excel = true;
            let queryString = $.param(filterd_params)
            let url = route +'?'+queryString;
            window.open(url,'_blank');
        });

        $('#download-daywise-attendance-excel').on('click',function (e){
            e.preventDefault();
            let route = $(this).data('href');
            let filterd_params = getDayWiseAttendanceFilterParam();
            filterd_params.download_excel = true;
            let queryString = $.param(filterd_params)
            let url = route +'?'+queryString;
            window.open(url,'_blank');
        })

        $('.reset').click(function(event){
            event.preventDefault();
            let currentDate = $(this).data('date');
            $('#attendance_date').val(currentDate);
        })

        $('.detailReset').click(function(event){
            event.preventDefault();
            let CurrentYear = $(this).data('year');
            let currentMonth = $(this).data('month');
            $('#year').val(CurrentYear);
            $('#month').val(currentMonth);
        })

        $('.dayAttendance').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });


    });
</script>
