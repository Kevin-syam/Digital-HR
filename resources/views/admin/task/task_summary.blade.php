<div class="col-lg-4 sidebar-list position-relative">
    <div class="position-sticky top-0">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Task Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-border">
                    <tbody>
                    <tr>
                        <td>Project:</td>
                        <td class="text-end">
                            <a href="{{route('admin.projects.show',$taskDetail->project->id)}}"> {{ucfirst($taskDetail->project->name)}}</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Created:</td>
                        <td class="text-end text-success">{{\App\Helpers\AppHelper::formatDateForView($taskDetail->start_date)}}</td>
                    </tr>
                    <tr>
                        <td>Deadline:</td>
                        <td class="text-end text-danger">{{\App\Helpers\AppHelper::formatDateForView($taskDetail->end_date)}}</td>
                    </tr>
                    <tr>
                        <td>Priority:</td>
                        <td class="text-end">
                            <span class="btn btn-secondary btn-xs"> {{ucfirst($taskDetail->priority)}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Remaining Days:</td>
                        <td class="text-end"><span class="badge badge-soft-success text-end d-inline-block float-end">
                                        {{$taskDetail->taskRemainingDaysToComplete()}} days left
                                        </span>
                        </td>
                    </tr>
                    <tr class="border-0">
                        <td>Status:</td>
                        <td class="text-end"><span class="btn btn-{{$status[$taskDetail->status]}} btn-xs">
                                            {{\App\Helpers\PMHelper::STATUS[$taskDetail->status]}}</span>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Task Members</h5>
            </div>
            <div class="card-body">
                @foreach($taskDetail->assignedMembers as $key => $value)
                    <div class="member-section-inner d-flex align-items-center mb-3">
                        <div class="member-section-image me-2">
                            <img class="rounded-circle" style="object-fit: cover"
                                 src="{{ $value->user->avatar ?
                                            asset(\App\Models\User::AVATAR_UPLOAD_PATH.$value->user->avatar):
                                            asset('assets/images/img.png')
                                        }}"
                                 alt="profile">
                        </div>
                        <div class="member-section-heading">
                            <h5>{{$value->user->name}}</h5>
                            <p class="small text-muted">
                                {{$value->user->post?->post_name ?? 'N/A'}}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
