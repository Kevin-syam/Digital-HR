<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\ContentManagementRepository;
use Exception;

class PrivacyPolicyController extends Controller
{

    private $view = 'admin.privacyPolicy.';

    private ContentManagementRepository $contentMgmtRepo;

    public function __construct(ContentManagementRepository $contentMgmtRepo)
    {
        $this->contentMgmtRepo = $contentMgmtRepo;
    }

    public function index()
    {
        try {
            $contentType = 'app-policy';
            $select = ['id','company_id','description'];
            $with = ['company:name,id'];
            $privacyPolicy = $this->contentMgmtRepo->findPrivacyPolicyByContentType($contentType, $select,$with);
            if (!$privacyPolicy) {
                throw new Exception('privacy policy not found');
            }
            return view($this->view . 'index', compact('privacyPolicy'));
        } catch (Exception $exception) {
            abort(404, 'Page Not Found');
        }
    }

}
