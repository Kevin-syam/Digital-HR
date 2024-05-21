<?php

namespace App\Imports;

use App\Helpers\AppHelper;
use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HolidaysImport implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       $holidayDetail = Holiday::where('event_date',date('Y-m-d',strtotime($row['event_date'])))->first();
       if($holidayDetail){
          Holiday::destroy($holidayDetail->id);
       }
        return new Holiday([
            "event" => $row['event'],
            "event_date" => AppHelper::ifDateInBsEnabled() ?
                AppHelper::dateInYmdFormatNepToEng($row['event_date']) :
                date('Y-m-d',strtotime($row['event_date'])),
            "note" => $row['note'],
            "is_active" => 1,
            "company_id" => AppHelper::getAuthUserCompanyId()
        ]);


    }
}
