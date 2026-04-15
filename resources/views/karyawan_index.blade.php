<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Data Karyawan</h1>
                <p class="text-slate-500 text-sm">Kelola informasi staff dan jabatan mereka di sini.</p>
            </div>
            <button onclick="toggleModal('modal-tambah')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-semibold transition flex items-center gap-2 shadow-lg shadow-indigo-200">
                <i class="fa fa-plus text-xs"></i> Tambah Karyawan
            </button>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6 flex gap-4">
            <form action="/" method="GET" class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." class="flex-1 border border-slate-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium transition">Cari</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-bottom border-slate-100 text-slate-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $k)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $k->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-indigo-50 text-indigo-600 text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">
                                {{ $k->jabatan->nama_jabatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-3">
                            <button onclick="editKaryawan({{ $k->id }}, '{{ $k->nama }}', {{ $k->jabatan_id }})" class="text-blue-500 hover:text-blue-700 transition">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form id="delete-form-{{ $k->id }}" action="{{ route('karyawan.destroy', $k->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $k->id }})" class="text-red-400 hover:text-red-600 transition">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-400">Data tidak tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $data->links() }}</div>
    </div>

    <div id="modal-tambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl animate-fade-in">
            <h3 class="text-xl font-bold mb-4 text-slate-800">Tambah Staff Baru</h3>
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                    <select name="jabatan_id" class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($jabatans as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('modal-tambah')" class="px-4 py-2 text-slate-500 hover:text-slate-700">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-xl font-bold mb-4 text-slate-800">Edit Data Staff</h3>
            <form id="form-edit" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="edit-nama" name="nama" required class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                    <select id="edit-jabatan" name="jabatan_id" class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($jabatans as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('modal-edit')" class="px-4 py-2 text-slate-500 hover:text-slate-700">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle Modal
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        // Handle Edit
        function editKaryawan(id, nama, jabatan_id) {
            document.getElementById('form-edit').action = '/karyawan/' + id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-jabatan').value = jabatan_id;
            toggleModal('modal-edit');
        }

        // SweetAlert Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
</html>