@extends('index')

@section('content')
<div class="container">
    @if(Auth::user()->role == 'user' || Auth::user()->role == 'admin' || Auth::user()->role == 'koordinator')
        <div class="container-info">
            <div class="stat-card">
                <div class="btn-btn warning">
                    @if(Auth::user()->role == 'koordinator')
                        <a href="{{ route('admin.absenDetail') }}" class="btn btn-warning btn-sm">
                            Detail Absen
                        </a>
                    @endif
                </div>
                <br>
            </div>

                   @if(Auth::user()->role == 'user' || Auth::user()->role == 'admin')
                @if(Auth::user()->tgl_expired <= now())
                    <p>Anda sudah melewati masa magang.</p>
                @else
                    @php
                    $hasAttendedToday = \App\Models\Absensi::where('id_user', Auth::user()->id)
                        ->whereDate('tgl', now()->format('Y-m-d'))
                        ->exists();
                    @endphp

                    @if($hasAttendedToday)
                        <p>Anda sudah melakukan absen hari ini.</p>
                    @else
                    <form action="{{ route('absensi.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="nama" class="col-sm-3 col-form-label">Nama :</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" value="{{ Auth::user()->name }}" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="divisi" class="col-sm-3 col-form-label">Divisi :</label>
                            <div class="col-sm-9">
                                <input type="text" name="divisi" value="{{ Auth::user()->divisi }}" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="text" id="currentDay" name="tgl" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly hidden>
                        </div>
                        <div class="form-group row">
                            <input type="text" name="id_user" value="{{ Auth::user()->id }}" class="form-control" required readonly hidden>
                        </div>
                        @if(now()->setTimezone('Asia/Jakarta')->format('H') >= 8)
                            <div class="form-group row">
                                <label for="alasan" class="col-sm-3 col-form-label">Alasan Telat :</label>
                                <div class="col-sm-9">
                                    <input type="text" name="alasan" class="form-control" required>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">HADIR</button>
                            </div>
                        </div>
                    </form>
                @endif
            @endif
            @endif
        </div>
    @endif
@endsection
