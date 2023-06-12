    @foreach ($barangStock as $item)
    <option value="{{ $item->ID }}">{{ $item->ID }} - {{ $item->Description }} ({{ $item->Unit }})</option>
    @endforeach
