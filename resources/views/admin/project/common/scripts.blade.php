<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('assets/js/tinymce.js')}}"></script>
<script src="{{asset('assets/js/imageuploadify.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>


<script>
    $(document).ready(function () {

        $('.errorClient').hide();

        $("#member").select2({
            placeholder: "Assign member to project"
        });

        $("#projectLead").select2({
            placeholder: "Add project leaders"
        });

        $("#employeeAdd").select2({
            placeholder: "Add employee to project"
        });

        $("#filter").select2({
            placeholder: "Search by member"
        });

        $("#project_name").select2({
            placeholder: "Search by Project"
        });

        $("#image-uploadify").imageuploadify();

        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });

        $('.toggleStatus').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to change status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }else if (result.isDenied) {
                    // (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                }
            })
        })

        $('.delete').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Project Detail ?',
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

        $('.documentDelete').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Project Document ?',
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

        $('.reset').click(function(event){
            event.preventDefault();
            $('#projectName').val('');
            $('#status').val('');
            $('#priority').val('');
            $('#filter').select2('destroy').find('option').prop('selected', false).end().select2();
            $("#filter").select2({
                placeholder: "Search by member"
            });
            $('#project_name').select2('destroy').find('option').prop('selected', false).end().select2();
            $("#project_name").select2({
                placeholder: "Search by project"
            });
        })

        $('#client_form').on('submit',function(e){
            e.preventDefault()
            let url = $(this).attr('action');
            let formData = new FormData(this);
            $.ajax({
                url: url,
                type: 'post',
                data: formData,
                dataType : 'json',
                contentType: false,
                processData: false,
            }).done(function(response) {
                if(response.status_code == 200){
                    $('#email').val('');
                    $('#clientName').val('');
                    $('#contact_no').val('');
                    $('#address').val('');
                    $('#country').val('');
                    $('#avatar').val('');
                    $('#client_id').append('<option value="'+response.data.id+'" selected>'+response.data.name+'</option>');
                    setTimeout(function(){
                        $('#addslider').modal('hide');
                        },600
                    );
                }
            }).error(function(error){
                let errorMessage = error.responseJSON.message;
                $('#showErrorMessageResponse').removeClass('d-none');
                $('.errorClient').show();
                $('.errorClientMessage').text(errorMessage);
                $('div.alert.alert-danger').not('.alert-important').delay(5000).slideUp(900);
            });
        });

        $('.startDate').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            disableAfter: "2089-12-30",
        });

        $('.deadline').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            disableAfter: "2089-12-30",
        });
    });

</script>
