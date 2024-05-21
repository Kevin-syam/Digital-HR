<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use App\Repositories\PostRepository;


class ScoreController extends Controller
{
    //
    private $view = 'admin.scoreKpi.';

    private PostRepository $postRepo;
    private DepartmentRepository $departmentRepo;

    public function __construct(PostRepository $postRepo, DepartmentRepository $departmentRepo)
    {
        $this->postRepo = $postRepo;
        $this->departmentRepo = $departmentRepo;
    }


    public function index(Request $request)
    {
        $this->authorize('list_scoreKpi');
        try {
            $filterParameters = [
                'name' =>  $request->name ?? null,
                'department' => $request->department ?? null
            ];
            $postSelect = ['*'];
            $with = ['department:id,dept_name','employees:id,name,post_id,avatar'];
            $departments = $this->departmentRepo->pluckAllDepartments();
            $posts = $this->postRepo->getAllDepartmentPosts($filterParameters,$with,$postSelect);
            return view($this->view . 'index', compact('posts',
                'filterParameters',
                'departments'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_post');
        try {
            $with = [];
            $select = ['id', 'dept_name'];
            // $departments = $this->departmentRepo->pluckAllDepartments();
            $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            
            return view($this->view . 'create', compact('departmentDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
