<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#showLeaveReason', function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            $.get(url, function (data) {
                $('.modal-title').html('Leave Reason');
                $('#description').text(data.data.reasons);
                $('#adminRemark').text(data.data.admin_remark);
                $('#addslider').modal('show');
            })
        }).trigger("change");

        $('body').on('click', '#leaveRequestUpdate', function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            let status = $(this).data('status');
            let remark = $(this).data('remark');
            $('.modal-title').html('Leave Status Update');
            $('#updateLeaveStatus').attr('action',url)
            $('#status').val(status)
            $('#remark').val(remark)
            $('#statusUpdate').modal('show');
        });

        $('.reset').click(function(event){
            event.preventDefault();
            $('#requestedBy').val('');
            $('#leaveType').val('');
            $('#month').val('');
            $('#status').val('');
            $('#year').val('');
        })
    });

</script>
