<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('assets/js/tinymce.js')}}"></script>
<script src="{{asset('assets/js/imageuploadify.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    $(document).ready(function (e) {

        let oldData = {{!isset($taskDetail) && old('name') ? 1 : 0 }};
        if(oldData){
            loadProjectMember();
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#taskMember").select2({
            placeholder: "Assign member to task"
        });

        $("#project").select2({
            placeholder: "Select Project"
        });

        $("#filter").select2({
            placeholder: "Search by member"
        });

        $("#projectFilter").select2({
            placeholder: "Search by project"
        });

        $("#taskName").select2({
            placeholder: "Search by Task Name"
        });

        $("#image-uploadify").imageuploadify();

        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });

        $('.formChecklist').hide();

        let project = $('#project').val();
        (project != null) ? $('.taskMemberAssignDiv').show():  $('.taskMemberAssignDiv').hide();


        $('#project').on('change',function(e){
            e.preventDefault();
            loadProjectMember();
        });

        function loadProjectMember(){
            let projectSelected = $('#project option:selected').val();
            if (projectSelected) {
                $('.taskMemberAssignDiv').show();
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin/projects/get-assigned-members/') }}" + '/' + projectSelected ,
                }).done(function(response) {
                    $('#taskMember').empty();
                    response.data.forEach(function(data) {
                        $('#taskMember').append('<option  value="'+data.id+'" >'+(data.name)+'</option>');
                    });
                });
            }
        }


        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            let status = $(this).prop('checked') === true ? 1 : 0;
            let href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change task active status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }else if (result.isDenied) {
                    (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                }
            })
        })

        $('body').on('click', '#checklistToggle', function (event) {
            event.preventDefault();
            let status = $(this).prop('checked') ? 1 : 0;
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
                    (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                }
            })
        })

        $('.delete').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Task Detail ?',
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

        $('body').on('click', '#delete', function (event) {
            event.preventDefault();
            let title = $(this).data('title');
            let href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to Delete '+title+ '?',
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

        $('#createChecklist').click(function(e){
            $('.formChecklist').removeClass('d-none');
            let text = $(this).text();
            (text == 'Create Checklist') ? $(this).text('Close') : $(this).text('Create Checklist');
            $('.formChecklist').toggle(500);
        })

        $('#addChecklist').on('click',function(event){
            event.preventDefault();
            let removeButton = '<div class="col-lg-2 text-center removeButton">'
                                +'<button type="button" class="btn btn-sm btn-danger remove" title="remove checklist" id="removeChecklist"> Remove </button>'+
                          '</div>';
            $(".checklist").first().clone().append(removeButton).appendTo("#addTaskCheckList");
            $(".addButtonSection:last").remove();
        })

        $("#addTaskCheckList").on('click', '.remove', function(){
            $(this).closest(".checklist").remove();
        });

        $(".checklistAdd").click(function(e) {
            e.preventDefault();
            $('.formChecklist').removeClass('d-none');
            $('.formChecklist').show();
            $('html,body').animate({
                scrollTop: $('#taskAdd').offset().top - 100
            }, 600);
        });

        $('.reset').click(function(event){
            event.preventDefault();
            $('#taskName').val('');
            $('#status').val('');
            $('#priority').val('');
            $('#projectFilter').select2('destroy').find('option').prop('selected', false).end().select2();
            $("#projectFilter").select2({
                placeholder: "Search by member"
            });
            $('#filter').select2('destroy').find('option').prop('selected', false).end().select2();
            $("#filter").select2({
                placeholder: "Search by member"
            });
            $('#taskName').select2('destroy').find('option').prop('selected', false).end().select2();
            $("#taskName").select2({
                placeholder: "Search by Task Name"
            });
        });
    });

</script>
