<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MahasiswaController extends Controller
{
    #[OA\Get(
        path: '/api/mahasiswa',
        summary: 'List semua mahasiswa',
        tags: ['Mahasiswa'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function index() {
        return response()->json([
            'status'  => true,
            'message' => 'Data Mahasiswa',
            'data'    => Mahasiswa::latest()->get()
        ]);
    }

    #[OA\Post(
        path: '/api/mahasiswa',
        summary: 'Tambah mahasiswa baru',
        tags: ['Mahasiswa'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nim', type: 'string', example: '21TI003'),
                    new OA\Property(property: 'nama', type: 'string', example: 'Andi Wijaya'),
                    new OA\Property(property: 'jenis_kelamin', type: 'string', example: 'L'),
                    new OA\Property(property: 'kelas', type: 'string', example: 'TI-3A'),
                    new OA\Property(property: 'jurusan', type: 'string', example: 'Teknik Informatika'),
                    new OA\Property(property: 'tahun_masuk', type: 'integer', example: 2021),
                    new OA\Property(property: 'agama', type: 'string', example: 'Islam'),
                    new OA\Property(property: 'alamat_asal', type: 'string', example: 'Bandung'),
                    new OA\Property(property: 'alamat_sekarang', type: 'string', example: 'Kos Cihampelas'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Data berhasil disimpan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function store(Request $request) {
        $data = $request->validate([
            'nim'            => 'required|unique:mahasiswas|max:20',
            'nama'           => 'required|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'kelas'          => 'required|max:20',
            'jurusan'        => 'required|max:100',
            'tahun_masuk'    => 'required|digits:4',
            'agama'          => 'required|max:30',
            'alamat_asal'    => 'required',
            'alamat_sekarang'=> 'nullable',
            'foto'           => 'nullable|max:255',
            'link_ig'        => 'nullable|max:100',
            'link_linkedin'  => 'nullable|max:100',
        ]);
        $mhs = Mahasiswa::create($data);
        return response()->json([
            'status'  => true,
            'message' => 'Mahasiswa berhasil disimpan',
            'data'    => $mhs
        ], 201);
    }

    #[OA\Get(
        path: '/api/mahasiswa/{id}',
        summary: 'Detail mahasiswa by ID',
        tags: ['Mahasiswa'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Data ditemukan'),
            new OA\Response(response: 404, description: 'Data tidak ditemukan'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function show($id) {
        $mhs = Mahasiswa::find($id);
        if (!$mhs) return response()->json([
            'status'  => false,
            'message' => 'Tidak ditemukan'
        ], 404);
        return response()->json(['status' => true, 'data' => $mhs]);
    }

    #[OA\Put(
        path: '/api/mahasiswa/{id}',
        summary: 'Update data mahasiswa',
        tags: ['Mahasiswa'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nama', type: 'string', example: 'Budi Santoso'),
                    new OA\Property(property: 'kelas', type: 'string', example: 'TI-3B'),
                    new OA\Property(property: 'alamat_sekarang', type: 'string', example: 'Kos Cihampelas No.10'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Data berhasil diperbarui'),
            new OA\Response(response: 404, description: 'Data tidak ditemukan'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function update(Request $request, $id) {
        $mhs = Mahasiswa::findOrFail($id);
        $data = $request->validate([
            'nim'            => 'sometimes|max:20|unique:mahasiswas,nim,'.$id,
            'nama'           => 'sometimes|max:100',
            'jenis_kelamin'  => 'sometimes|in:L,P',
            'kelas'          => 'sometimes|max:20',
            'jurusan'        => 'sometimes|max:100',
            'tahun_masuk'    => 'sometimes|digits:4',
            'agama'          => 'sometimes|max:30',
            'alamat_asal'    => 'sometimes',
            'alamat_sekarang'=> 'nullable',
            'foto'           => 'nullable|max:255',
            'link_ig'        => 'nullable|max:100',
            'link_linkedin'  => 'nullable|max:100',
        ]);
        $mhs->update($data);
        return response()->json([
            'status'  => true,
            'message' => 'Data diperbarui',
            'data'    => $mhs
        ]);
    }

    #[OA\Delete(
        path: '/api/mahasiswa/{id}',
        summary: 'Hapus data mahasiswa',
        tags: ['Mahasiswa'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Data berhasil dihapus'),
            new OA\Response(response: 404, description: 'Data tidak ditemukan'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function destroy($id) {
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Data dihapus'
        ]);
    }
    #[OA\Post(
    path: '/api/mahasiswa/{id}/foto',
    summary: 'Upload foto mahasiswa',
    tags: ['Mahasiswa'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
    ],
    responses: [
        new OA\Response(response: 200, description: 'Foto berhasil diupload'),
        new OA\Response(response: 404, description: 'Data tidak ditemukan'),
    ]
    )]
    public function uploadFoto(Request $request, $id) {
        $mhs = Mahasiswa::findOrFail($id);
        $request->validate([
        'foto' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $path = $request->file('foto')->store('foto_mahasiswa', 'public');
        $mhs->update(['foto' => $path]);
        return response()->json([
            'status'  => true,
            'message' => 'Foto berhasil diupload',
            'foto'    => asset('storage/' . $path),
        ]);
    }
}