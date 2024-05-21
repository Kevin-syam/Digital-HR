<script>

    $(document).ready(function () {

        $("#team_meeting").select2({
            placeholder: "Select Meeting Participants"
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.delete').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Team Meeting ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        $('.removeImage').click(function (event){
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Remove Image ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });

        $('body').on('click', '#showMeetingDescription', function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            $.get(url, function (data) {
                $('.meetingTitle').html('Meeting Detail');
                $('.title').text(data.data.title);
                $('.date').text(data.data.meeting_date);
                $('.time').text(data.data.time);
                $('.venue').text(data.data.venue);
                $('.publish_date').text(data.data.meeting_published_at);
                $('.description').text(data.data.description);
                $('.image').attr('src',data.data.image);

                $('#addslider').modal('show');
            })
        }).trigger("change");

        $('.reset').click(function(event){
            event.preventDefault();
            $('#participator').val('');
            $('.fromDate').val('');
            $('.toDate').val('');
        });

        $('#fromDate').nepaliDatePicker({
            language: "english",
            dateFormat: "MM/DD/YYYY",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });

        $('#toDate').nepaliDatePicker({
            language: "english",
            dateFormat: "MM/DD/YYYY",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });
    });

</script>
