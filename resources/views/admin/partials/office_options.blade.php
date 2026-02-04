<option value="">Filter by Office</option>
@foreach($offices as $off)
    <option value="{{ $off }}" {{ request('office') === $off ? 'selected' : '' }}>
        {{ $off }}
    </option>
@endforeach
