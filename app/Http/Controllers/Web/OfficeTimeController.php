<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OfficeTime;
use App\Repositories\CompanyRepository;
use App\Repositories\OfficeTimeRepository;
use App\Requests\OfficeTime\OfficeTimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficeTimeController extends Controller
{
    private $view = 'admin.officeTime.';

    private OfficeTimeRepository $officeTimeRepo;
    private CompanyRepository $companyRepo;

    public function __construct(OfficeTimeRepository $officeTimeRepo, CompanyRepository $companyRepo)
    {
        $this->officeTimeRepo = $officeTimeRepo;
        $this->companyRepo = $companyRepo;
    }

    public function index()
    {
        $this->authorize('list_office_time');
        try {
            $with=[];
            $officeTimes = $this->officeTimeRepo->getAllCompanyOfficeTime($with);
            return view($this->view . 'index', compact('officeTimes'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_office_time');
        try{
            $select = ['id','name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select);
            $shift = OfficeTime::SHIFT;
            $category = OfficeTime::CATEGORY;
            return view($this->view.'create',
                compact('companyDetail','shift','category')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(OfficeTimeRequest $request)
    {
        $this->authorize('create_office_time');
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
            $this->officeTimeRepo->store($validatedData);
            DB::commit();
            return redirect()->route('admin.office-times.index')
                ->with('success', 'New Office Schedule Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $this->authorize('show_office_time');
            $select = ['opening_time','closing_time','description','shift'];
            $officeTimes = $this->officeTimeRepo->findCompanyOfficeTimeById($id,$select);
            return response()->json([
                'data' => $officeTimes,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function edit($id)
    {
        $this->authorize('edit_office_time');
        try{
            $officeTime = $this->officeTimeRepo->findCompanyOfficeTimeById($id);
            $select = ['id','name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select);
            $shift = OfficeTime::SHIFT;
            $category = OfficeTime::CATEGORY;
            return view($this->view.'edit', compact('officeTime','companyDetail','shift','category'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function update(OfficeTimeRequest $request, $id)
    {
        $this->authorize('edit_office_time');
        try{
            $validatedData = $request->validated();
            $officeTime = $this->officeTimeRepo->findCompanyOfficeTimeById($id);
            if(!$officeTime){
                throw new \Exception('Office Time Detail Not Found',404);
            }
            DB::beginTransaction();
            $this->officeTimeRepo->update($officeTime,$validatedData);
            DB::commit();
            return redirect()->route('admin.office-times.index')
                ->with('success', 'Office Time Detail Updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_office_time');
        try {
            DB::beginTransaction();
            $this->officeTimeRepo->toggleStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Status changed  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_office_time');
        try {
            $officeTime = $this->officeTimeRepo->findCompanyOfficeTimeById($id);
            if (!$officeTime) {
                throw new \Exception('Company Office Time Detail Not Found', 404);
            }
            DB::beginTransaction();
            $this->officeTimeRepo->delete($officeTime);
            DB::commit();
            return redirect()->back()->with('success', 'Office schedule Deleted  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
