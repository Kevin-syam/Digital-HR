<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Repositories\ContentManagementRepository;
use App\Resources\StaticPageContent\StaticPageContentCollection;
use App\Resources\StaticPageContent\StaticPageContentResource;
use Illuminate\Http\JsonResponse;
use Exception;

class StaticPageContentApiController
{

    private ContentManagementRepository $contentManagementRepos;

    public function __construct(ContentManagementRepository $contentManagementRepos)
    {
        $this->contentManagementRepos = $contentManagementRepos;
    }

    public function getStaticPageContentByContentType($contentType): JsonResponse
    {
        try {
            $companyId = AppHelper::getAuthUserCompanyId();
            $staticPageContent = $this->contentManagementRepos->getCompanyActiveContentByContentType($companyId, $contentType);
            if(!$staticPageContent) {
                throw new Exception('Data not Found',404);
            }
            $staticPageContent = new StaticPageContentResource($staticPageContent);
            return AppHelper::sendSuccessResponse('Data Found', $staticPageContent);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getCompanyRulesDetail(): JsonResponse
    {
        try {
            $companyId = AppHelper::getAuthUserCompanyId();
            $contentType = 'company-rules';
            $companyRules = $this->contentManagementRepos->getAllActiveCompanyRules($companyId,$contentType);
            $companyRules = new StaticPageContentCollection($companyRules);
            return AppHelper::sendSuccessResponse('Data Found', $companyRules);
        }catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getStaticPageContentByContentTypeAndTitleSlug($contentType,$titleSlug): JsonResponse
    {
        try {
            $companyId = AppHelper::getAuthUserCompanyId();
            $staticPageContent = $this->contentManagementRepos->getStaticPageContentByContentTypeAndTitleSlug($companyId, $contentType,$titleSlug);
            if(!$staticPageContent){
                throw new Exception('Content Not found',400);
            }
            $staticPageContent = new StaticPageContentResource($staticPageContent);
            return AppHelper::sendSuccessResponse('Data Found', $staticPageContent);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }



}
