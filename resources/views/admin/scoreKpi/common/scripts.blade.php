<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                // width:'500px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }else if (result.isDenied) {
                    (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                }
            })
        })

        $('.deletePost').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Post ?',
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

        $('body').on('click', '#showEmployee', function (e) {
            e.preventDefault();
            let employee = $(this).data('employee');
            $('.employee').remove();
            $('.modal-title').html('Employee List');
            if(employee.length > 0 ){
                $('.postEmptyCase').addClass('d-none');
                    employee.forEach(function(data){
                       let avatar = data.avatar ? '{{asset(\App\Models\User::AVATAR_UPLOAD_PATH )}}'+'/'+data.avatar : '{{asset('assets/images/img.png')}}';
                       $('.employeeList').append(
                           '<div class="col-lg-6 d-flex align-items-center mb-3 employee">'+
                               '<img class="rounded-circle w-25 me-2 employeeImage"' + 'style="object-fit: cover" '+
                                    'src="'+avatar+'"'+
                                    'alt="profile">'+
                                   '<span class="employeeName">'+data.name+'</span>'+
                           '</div>'
                    );
                });
            }else{
                $('.postEmptyCase').removeClass('d-none');
            }
            $('#addslider').modal('show');
        }).trigger("change");
    });



</script>
