

<div class="row">
    
    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Department <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="dept_id" required>

            <option value=""  disabled selected >Select Department</option>
            @foreach($departmentDetail as $key => $department)
                <option value="{{ $department->id }}">
                    {{ucfirst($department->dept_name)}}
                </option>
            @endforeach
        </select>
    </div>

    
    <div class="col-lg-4 mb-3">
        <label class="mb-1">Periode</label>
        <div class="col-sm-12">
            <input type="month" class="form-control" name="period"
                required>
        </div>
    </div>


    {{-- <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value=""  disabled>Select status</option>
            <option value="1" {{ isset($postDetail) && ($postDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($postDetail) && ($postDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div> --}}

    {{-- <div id="kpiScoreData">

    </div> --}}

    @foreach($departmentDetail as $key => $department)
        
        <div class="kpi-fields" id="kpiScoreData-{{ $department->id }}" data-department-id="{{ $department->id }}" style="width:100%; display:none">
            {{  $department->dept_name }}
            @foreach($department->kpis as $index => $kpi)
                {{-- @if ($department->id == true) --}}
                
                <div class="col-lg-4 mb-3">
                    {{-- <div class="form-group"> --}}
                    <label>{{ $kpi->kpi_desc }} <span style="font-weight:100; text-color:grey;"> (Range: 0-{{ $kpi->kpi_target }})</span></label>
                    <input type="number" name="kpis[{{ $kpi->id }}][realisation]" class="form-control" placeholder="Enter score" min=0 max={{ $kpi->kpi_target }}>
                    {{-- </div> --}}
                </div>
                <input type="hidden" name="kpis[{{ $kpi->id }}][weight]" class="form-control"  value={{ $kpi->weight }} readonly>
                <input type="hidden" name="kpis[{{ $kpi->id }}][kpi_target]" class="form-control" value={{ $kpi->kpi_target }} readonly>
                
                {{-- @endif --}}
            @endforeach
        </div>
    @endforeach


    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> Add Scores</button>
    </div>
</div>


<script>
    

    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('exampleFormControlSelect1');
        const kpiFields = document.querySelectorAll('.kpi-fields');
        // const kpiFields = document.getElementById('kpiScoreData'+[1]);

        
        
        departmentSelect.addEventListener('change', function() {
            const selectedDepartmentId = this.value;
            
            

            kpiFields.forEach(function(field) {
                if (field.getAttribute('data-department-id') === selectedDepartmentId) {
                    console.log(field)
                    field.style.display = 'block';
                } else {
                //     console.log("Ok")
                    field.style.display = 'none';
                    field.querySelectorAll('input').forEach(input => input.disabled = true);
                }
            });

            // Re-enable the input fields for the selected department
        var selectedFields = document.querySelector('#kpiScoreData-' + selectedDepartmentId);
        selectedFields.querySelectorAll('input').forEach(input => input.disabled = false);
            
        });
    });
//     $(document).ready(function() {
//         $('#exampleFormControlSelect1').change(function() {
//             var selectedDepartment = $(this).val();
//             var token = $('meta[name="csrf-token"]').attr('content');
//             $.ajax({
//                 url: '/getKPIs', // Replace with your server endpoint
//                 method: 'GET',
//                 data: {
//                     department: selectedDepartment,
//                     _token: token
//                 },
//                 success: function(response) {
//                     // Clear previous KPIs
//                     $('#kpiScoreData').empty();
//                     // Add new KPIs to the form
//                     $.each(response, function(index, kpi) {
//                         var kpiHtml = '<div class="form-group">';
//                         kpiHtml += '<label>' + kpi.kpi_desc + '</label>';
//                         kpiHtml += '<input type="number" name="kpis[' + kpi.id + '][score]" class="form-control" placeholder="Enter score">';
//                         kpiHtml += '</div>';
//                         $('#kpiScoreData').append(kpiHtml);
//                     });
//                 },
//                 error: function(xhr, status, error) {
//                     console.error(error);
//                 }
//             });
//         });
// });

</script>