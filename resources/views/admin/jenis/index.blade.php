@extends('admin.layout.app')
@section('konten')
    {{-- Ini Batas Model --}}

    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Jenis Produk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/jenis_produk/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="nama" class="form-control" id=""
                            placeholder="Masukkan Jenis Produk">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    {{-- akhir Modal --}}

    <div class="container-fluid px-4">
        <h1 class="mt-4">Jenis Produk</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active">Tables</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <a href="" class="btn btn-lg btn-primary" 
                data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa-solid fa-square-plus"></i></a>
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Produk</th>
                            {{-- <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th> --}}
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Jenis Produk</th>
                            {{-- <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th> --}}
                        </tr>
                    </tfoot>
                    <tbody>
                        @php $no=1 @endphp
                        @foreach ($jenis as $j)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $j->nama }}</td>
                                {{-- <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011/04/25</td>
                        <td>$320,800</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection