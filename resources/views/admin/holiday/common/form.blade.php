<div class="row">
    <div class="col-lg-6 mb-3">
        <label for="event" class="form-label">Event<span style="color: red">*</span></label>
        <input type="text" class="form-control" id="event" required name="event" value="{{ ( isset($holidayDetail) ? ($holidayDetail->event): old('event') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="event_date" class="form-label">Event Date<span style="color: red">*</span></label>
        <input class="form-control" name="event_date" value="{{(isset($holidayDetail) ? ($holidayDetail->event_date): old('event_date') )}}" required
            @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                  type="text"
                  id="eventDate"
                  placeholder="yyyy/mm/dd"
            @else
                type="date"
            @endif
        />
    </div>

    <div class="col-lg-12 mb-3">
        <label for="note" class="form-label">Description</label>
        <textarea class="form-control" name="note" id="tinymceExample" >{{ ( isset($holidayDetail) ? $holidayDetail->note: old('note') )}}</textarea>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($holidayDetail)? 'edit-2':'plus'}}"></i>
            {{isset($holidayDetail)? 'Update':'Create'}} Holiday
        </button>
    </div>
</div>
