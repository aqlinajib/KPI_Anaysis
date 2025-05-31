<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sop;
use Illuminate\Support\Facades\Storage;

class SOPController extends Controller
{

    public function update(Request $request, $id)
    {
        $sop = sop::findOrFail($id);
        $sop->nama = $request->nama;
        $sop->divisi = $request->divisi;
        $sop->status = $request->status;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $tujuan_upload = 'public/uploads';
            $file->storeAs($tujuan_upload, $nama_file);

            // Hapus file lama jika ada
            if ($sop->file) {
                Storage::delete('public/uploads/' . $sop->file);
            }

            $sop->file = $nama_file;
        }

        $sop->save();

        return redirect('sop/admin')->with('success', 'Data SOP berhasil diupdate.');
    }
}
