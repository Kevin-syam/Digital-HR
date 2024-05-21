

<div class="row">
    {{-- <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="company_id">
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div> --}}


    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Department <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" required>

            <option value=""  selected disabled >Select Department</option>
            @foreach($departmentDetail as $key => $department)
                <option value="{{ $department->id }}">
                    {{ucfirst($department->dept_name)}}
                </option>
            @endforeach
        </select>
    </div>




    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table">
                    <thead>
                      <tr>
                        <th id="item">Item</th>
                        <th >KPI</th>
                        <th >Bobot KPI</th>
                        <th>Target</th>
                        <th>Satuan</th>
                        <th>Departemen</th>
                        <th>Keterangan</th>
                      </tr>
                      <thead>
                        <tbody id="tb">
                        </tbody>
                  </table>
                  
                  
                  <button type="button" id="add">Tambah Indikator</button>
            </div>
            
        </div>
    </div>


    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($departmentsDetail)? 'Update':'Create'}} KPI</button>
    </div>
</div>

<script>
    

    // Dynamic Table
    const tb = document.getElementById("tb");
    const columnNumber = document.querySelectorAll("#table thead tr th").length - 1;
    
     // Function to update department input field
     function updateDepartmentInput(value) {
        const departmentInputs = document.querySelectorAll('.deptname');
        departmentInputs.forEach(input => {
            input.value = value;
        });
    }

    // Event listener for select tag input
    document.getElementById('exampleFormControlSelect1').addEventListener('change', function() {
        const selectedDepartment = this.value;
        updateDepartmentInput(selectedDepartment);
    });

    // dynamic table
    let cnt = 1;
    document.getElementById("add").addEventListener("click",() => {
        // saving current text in input field
        const inputValues = Array.from(document.querySelectorAll('.TableInput')).map(input => input.value);
        
        // Append new row(s)
        // {[...Array(columnNumber).keys()].map(i => inp).join("")}
        tb.innerHTML += `<tr>
            <td class="right">${cnt++}</td>
            <td><input type="text" placeholder="KPI Description" class="TableInput" name="kpi_desc[]"/></td>
            <td><input type="number" min=0 max=100 placeholder="KPI Weight" class="TableInput" name="weight[]"/></td>
            <td><input type="number" min=0 placeholder="KPI Target" class="TableInput" name="kpi_target[]"/></td>
            <td><input type="text" placeholder="KPI Unit" class="TableInput" name="unit[]"/></td>
            <td><input type="text" placeholder="" class="deptname TableInput" value="1" name="dept_id[]" id="deptname" readonly/></td>
            <td>
                <select class="form-select" id="exampleFormControlSelect1" name="is_max[]">
                    <option value=""  disabled>Select Rule</option>
                    <option value="1">Max</option>
                    <option value="0">Min</option>
                </select>
            </td>
            
        </tr>`;
        
        // Restore the values of existing input fields
        const newInputs = document.querySelectorAll('.TableInput');
        newInputs.forEach((input, index) => {
            input.value = inputValues[index] || ''; 
        });
    })

    // Update department input field
    updateDepartmentInput(document.getElementById('exampleFormControlSelect1').value);

    // Departement multiple input handler
    // // var aselect = document.getElementById('exampleFormControlSelect1').value;
    // // document.getElementById('deptname').innerHTML = aselect;
    // $(".form-select").change(function () {
    //     $("#deptname").val($('#exampleFormControlSelect1').val());
    // });
</script>
