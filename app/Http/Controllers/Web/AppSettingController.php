<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Repositories\AppSettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppSettingController extends Controller
{
    private $view = 'admin.appSetting.';

    private AppSettingRepository $appSettingRepo;

    public function __construct(AppSettingRepository $appSettingRepo)
    {
        $this->appSettingRepo = $appSettingRepo;
    }

    public function index()
    {
        $this->authorize('list_app_setting');
        try{
            $select=['id','name','status'];
            $appSettings = $this->appSettingRepo->getAllAppSettings($select);
            return view($this->view.'index',compact('appSettings'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $this->authorize('update_app_setting');
        try {
            DB::beginTransaction();
                $this->appSettingRepo->toggleStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Status Changed  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeTheme()
    {
        try{
            $slug = 'dark-theme';
           $themeDetail = $this->appSettingRepo->findAppSettingDetailBySlug($slug);
           if(!$themeDetail){
               throw new \Exception('Theme Detail Not found',404);
           }
           $status = $this->appSettingRepo->toggleTheme($themeDetail);
           if($status){
               Cache::forget('theme');
               $theme = $themeDetail->status ? 'dark' : 'light' ;
               Cache::forever('theme', $theme);
           }
           return AppHelper::sendSuccessResponse('Theme Changed Successfully');
        }catch(\Exception $exception){
            return AppHelper::sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }


}
