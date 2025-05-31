
 @extends('index')

@section('content')                     
        <div class="container-info">
            <div class="stat-card">
                <div class="table-responsive pt-3">
                    {{-- Table or content for SOP could go here --}}
                </div>
                 @if(Auth::user()->role == 'admin')
                <div class="float-right mb-3 table-actions">
                    <a href="{{ route('admin.tambahsop') }}" class="btn btn-custom-color btn-sm">
                        <i class="mdi mdi-plus-circle-outline"></i> Tambah SOP
                    </a>
                </div>
            </div>
        </div>
        @else
  
        @endif

<h1>Daftar Dokumen</h1>
<br>

<table class="table table-bordered">
  <thead>
        <tr style="background-color: #7DC9BF;">
        <th>Nama SOP</th>
        <th>Divisi</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
  </thead>
  <tbody>
      @forelse ($sop as $sops)
          @if (Auth::user()->role == 'admin' || Auth::user()->role == 'koordinator')
              <tr>
                <td>{{ $sops->nama }}</td>
                <td>{{ $sops->divisi }}</td>
                <td>{{ $sops->status }}</td>
                <td>
                    <a href="{{ route('sop.show', $sops->id) }}" class="btn btn-warning btn-xs">
                        Download PDF
                    </a>
                    @if(Auth::user()->role == 'koordinator')
                        <a href="{{ route('updatesopdulu', $sops->id) }}" class="btn btn-primary btn-xs">TERBIKAN SOP</a>
                        <form action="{{ route('sop.delete', $sops->id) }}" method="post" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus SOP ini?')">Hapus</button>
                        </form>
                    @endif
                </td>
              </tr>
          @else
              @if ($sops->status == 'DITERBITKAN')
                  <tr>
                    <td>{{ $sops->nama }}</td>
                    <td>{{ $sops->divisi }}</td>
                    <td>{{ $sops->status }}</td>
                    <td>
                        <a href="{{ route('sop.show', $sops->id) }}" class="btn btn-warning btn-xs">
                            Download PDF
                        </a>
                    </td>
                  </tr>
              @endif
          @endif
      @empty
          <tr>
              <td colspan="4">Tidak ada SOP.</td>
          </tr>
      @endforelse
  </tbody>
</table>

@endsection

