<!-- show.blade.php -->

<h1>{{ $sop->nama }}</h1>


    <embed src="{{ asset('storage/uploads/' . $sop->file) }}" type="application/pdf" width="100%" height="100%">
