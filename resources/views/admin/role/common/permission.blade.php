
    @foreach($permissionGroupTypeList as $key => $permissionGroupType)
        <div class="mb-4 ">
            <h5 class="btn btn-dark" id="{{$permissionGroupType->slug}}">{{$permissionGroupType->name}} Permissions</h5>
        </div>
        <div class="row mb-2 {{$permissionGroupType->slug}}">
            @foreach($permissionGroupType->permissionGroups as $key=>$value)
                <div class="col-lg-3 col-md-6 mb-4 pb-4">
                    <div class="group-checkbox">
                        <div class="head-checkbox">
                            <div class="title-ch mb-3">
                                <h5>{{$value->name}} Module:</h5>
                            </div>

                            <div class="checkAll mb-2">
                                <label class="label-ch">
                                    <input class="js-check-all" type="checkbox" name=""
                                           data-check-all="website">
                                    <span class="text">Check All</span>
                                </label>
                            </div>
                        </div>
                        <ul class="js-check-all-target list-ch" data-check-all="website">
                            @foreach($value->getPermission as $keys => $permission)
                                @php
                                    $checked='';
                                    if(count($role_permission) > 0){
                                        if(in_array($permission->id,$role_permission)){
                                            $checked = "checked = 'checked'";
                                        }
                                    }
                                @endphp
                                <li class="item">
                                    <label class="label">
                                        <input class="module_checkbox"
                                               type="checkbox"
                                               id="{{$permission->permission_key}}"
                                               name="permission_value[]"
                                               value="{{$permission->id}}"
                                            {{$checked}}>
                                        <span class="text">{{$permission->name}}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach


    <div class="text-center">
        <button type="submit" class="btn btn-success btn-md">
            {{$isEdit ? 'Update': 'Save'}}
        </button>
    </div>
