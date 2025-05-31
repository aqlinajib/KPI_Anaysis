@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <h2>Detail Kerja</h2>
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'user')
    @if(Auth::user()->tgl_expired > now())
        <a href="{{ route('createHasilKerjaHarian', ['id_akumulasi' => request('id_akumulasi')]) }}" class="btn btn-primary">Tambah Data</a>
    @endif
@endif
 <p style="color: white;">...</p>
  <form method="GET" action="{{ route('kerjaPegawai', ['id_akumulasi' => request('id_akumulasi')]) }}">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
         <a href="{{ route('kerjaPegawai', ['id_akumulasi' => request('id_akumulasi')]) }}" class="btn btn-secondary">Reset</a>
    </form>
<br>
   @if(Auth::user()->role == 'admin' || Auth::user()->role == 'user')
    <table class="table">
        <thead>
              <tr style="background-color: #7DC9BF;">
                <th>NO</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Hasil Kerja Harian</th>
                <th>Tanggal</th>
                <th>Banyak Pekerjaan</th>
                <th>Bukti</th>
                <th>Aksi</th>
                @if(Auth::user()->role == 'admin,user')
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $nomorUrut = 1; @endphp
            @foreach ($hasil_kerja_harian->where('id_akumulasi', request('id_akumulasi')) as $kerja)
            @if (Auth::user()->divisi == $kerja->divisi) <!-- Hanya tampilkan jika divisi pengguna sama dengan divisi pada data -->
            <tr>
               <td>{{ $nomorUrut++ }}</td>
               <td>{{ $kerja->nama }}</td>
               <td>{{ $kerja->divisi }}</td>
               <td>{{ $kerja->hasil_kerja_harian }}</td>
               <td>{{ $kerja->tgl }}</td>
                <td>{{ $kerja->banyak_pekerjaan }}</td>
                <td>{{ $kerja->bukti }}</td>
                <td>
                    @if(Auth::user()->name == $kerja->nama) <!-- Hanya tampilkan tombol edit dan delete jika nama pengguna sama dengan nama pada data -->
                    <a href="{{ route('editHasilKerjaHarian', $kerja->id) }}" class="btn btn-warning">Edit</a>
                    <a href="#" onclick="showDeleteConfirmation({{ $kerja->id }})" class="btn btn-danger">
                        <i class="mdi mdi-delete delete-icon"></i> Delete
                    </a>
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
     <!-- Modal untuk Konfirmasi Penghapusan -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="deleteKandidat()">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="hideDeleteConfirmation()">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    function goBack() {
        window.history.back();
    }
    function showDeleteConfirmation(id) {
        var modal = $('#deleteConfirmationModal');
        modal.modal('show');
        modal.data('team-id', id);
    }

    function hideDeleteConfirmation() {
        $('#deleteConfirmationModal').modal('hide');
    }

    function deleteKandidat() {
        var id = $('#deleteConfirmationModal').data('team-id');
        var deleteUrl = "{{ route('destroyHasilKerjaHarian', '') }}/" + id;

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Entry deleted successfully.');
                location.reload();
            } else {
                alert('Failed to delete entry.');
            }
            hideDeleteConfirmation();
        })
        .catch(error => {
            console.error('Error:', error);
            hideDeleteConfirmation();
        });
    }
</script>
<style>
        .custom-buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .spacer {
            flex-grow: 1;
        }
    </style>
</div>
  @if(Auth::user()->role == 'koordinator')
<table class="table">
        <thead>
              <tr style="background-color: #7DC9BF;">
                <th>NO</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Hasil Kerja Harian</th>
                <th>Tanggal</th>
                <th>Banyak Pekerjaan</th>
                <th>Bukti</th>
                <th>Aksi</th>
                @if(Auth::user()->role == 'admin,user')
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $nomorUrut = 1; @endphp
            @foreach ($hasil_kerja_harian->where('id_akumulasi', request('id_akumulasi')) as $kerja)
            <!-- Hanya tampilkan jika divisi pengguna sama dengan divisi pada data -->
            <tr>
               <td>{{ $nomorUrut++ }}</td>
               <td>{{ $kerja->nama }}</td>
               <td>{{ $kerja->divisi }}</td>
               <td>{{ $kerja->hasil_kerja_harian }}</td>
               <td>{{ $kerja->tgl }}</td>
                <td>{{ $kerja->banyak_pekerjaan }}</td>
                <td>{{ $kerja->bukti }}</td>
                <td>
                    @if(Auth::user()->name == $kerja->nama) <!-- Hanya tampilkan tombol edit dan delete jika nama pengguna sama dengan nama pada data -->
                    <a href="{{ route('editHasilKerjaHarian', $kerja->id) }}" class="btn btn-warning">Edit</a>
                    <a href="#" onclick="showDeleteConfirmation({{ $kerja->id }})" class="btn btn-danger">
                        <i class="mdi mdi-delete delete-icon"></i> Delete
                    </a>
                    @endif
                </td>
            </tr>
        
            @endforeach
        </tbody>
    </table>
    @endif
@endsection
