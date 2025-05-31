@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h1>User Details</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Divisi</th>
                <th>Role</th>
                <th>Tgl Berakhir</th>
                <th>Status</th> <!-- Tambahkan kolom Status -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $users)
                <tr>
                    <td>{{ $users->name }}</td>
                    <td>{{ $users->email }}</td>
                    <td>{{ $users->divisi }}</td>
                    <td>{{ $users->role }}</td>
                    <td>{{ $users->tgl_expired }}</td>
                    <td>
                        @if(is_null($users->tgl_expired) || $users->tgl_expired > now())
                            Aktif
                        @else
                            Tidak Aktif
                        @endif
                    </td> <!-- Tambahkan logika status -->
                    <td>
                        <a href="{{ route('user.edit', $users->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('user.destroy', $users->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection