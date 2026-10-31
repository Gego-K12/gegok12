<fieldset class="border rounded p-3 my-3"><legend class="font-semibold">Student learning links (optional)</legend>
    @foreach(['homework_drive_url' => 'Homework upload folder (Google Drive)', 'recordings_url' => 'Lecture recordings', 'report_card_url' => 'Report cards / Excel or Google Sheet'] as $field => $label)
    <label class="block my-3">{{ $label }}<input type="url" name="{{ $field }}" class="tw-form-control block w-full" maxlength="2048" placeholder="https://…" value="{{ old($field, $journey->$field ?? '') }}"></label>
    @endforeach
    <p>These links are visible only to the student, linked parents and authorized staff. Share each external file/folder with the intended Google accounts; saving its link does not change Drive permissions.</p>
</fieldset>
