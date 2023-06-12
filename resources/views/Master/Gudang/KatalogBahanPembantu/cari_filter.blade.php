<label class="input-group-text btn-primary">Material</label>
<select class="form-control selectpicker my-select" id="cari" name="cari" data-live-search="true"
    data-style="border">
    <option selected="">Choose...</option>
    @foreach ($searchs as $search)
        <option value="{{ $search->SW }}">{{ $search->ID }} - {{ $search->Description }}</option>
    @endforeach
</select>

<script>
    $('.my-select').selectpicker();
</script>
