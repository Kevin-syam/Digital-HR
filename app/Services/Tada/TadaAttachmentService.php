<?php

namespace App\Services\Tada;

use App\Repositories\TadaAttachmentRepository;
use App\Repositories\TadaRepository;
use Exception;

class TadaAttachmentService
{
    public TadaAttachmentRepository $attachmentRepo;
    public TadaRepository $tadaRepo;

    public function __construct(TadaAttachmentRepository $attachmentRepo, TadaRepository $tadaRepo)
    {
        $this->attachmentRepo = $attachmentRepo;
        $this->tadaRepo = $tadaRepo;
    }

    public function store($validatedData)
    {
        try {
            $select = ['*'];
            $with = [];
            $tadaDetail = $this->tadaRepo->findTadaDetailById($validatedData['tada_id'], $select, $with);
            if (!$tadaDetail) {
                throw new Exception('Tada Detail Not Found', 404);
            }
            $tadaAttachment = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
            return $this->attachmentRepo->saveTadaAttachment($tadaDetail, $tadaAttachment);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function delete($attachmentDetail)
    {
        try {
            $status = $this->attachmentRepo->delete($attachmentDetail);
            if ($status) {
                $this->attachmentRepo->removeImageFromPublic($attachmentDetail['attachment']);
            }
            return $status;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findTadaAttachmentById($id, $with = [], $select = ['*'])
    {
        try {
            $attachmentDetail = $this->attachmentRepo->findTadaAttachmentById($id);
            if (!$attachmentDetail) {
                throw new Exception('Attachment Detail Not Found', 404);
            }
            return $attachmentDetail;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findEmployeeTadaAttachmentDetail($attachmentId,$select,$with)
    {
        try{
            $attachmentDetail = $this->attachmentRepo->findAttachmentDetailByAttachmentIdAndEmployeeId(getAuthUserCode(),$attachmentId,$select,$with);
            return $attachmentDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

}
