@extends('layouts.admin')
@section('nav__item-admin-siswa', 'active')

@section('title', 'Siswa')

@section('content')
<div class="card mb-4">
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 mb-2">
      <div class="d-flex align-items-start">
        <a href="#modal__create" data-toggle="modal" class="btn btn-primary mr-2">
          Tambah Siswa
        </a>
      </div>
    </div>
    <div class="col-12 col-sm-6 p-0 mb-2">
      <div class="d-flex align-items-end flex-column">
        <div>
          @if(session('success'))
          <div class="alert alert-success p-1 px-4 m-0">
            {{ session('success') }}
          </div>
          @elseif(session('error'))
          <div class="alert alert-danger p-1 px-4 m-0">
            {{ session('error') }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered data">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Card ID</th>
            <th scope="col">NIS</th>
            <th scope="col">Nama</th>
            <th scope="col">Kelas</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; ?>
          @foreach ($data_siswa as $siswa)
          <tr>
            <th scope="row">{{ $count }}</th>
            <td>{{ $siswa->card_id }}</td>
            <td>{{ $siswa->nis }}</td>
            <td>{{ $siswa->nama }}</td>
            <td>{{ $siswa->kelas }}</td>
            <td>
              <div class="dropdown d-inline mr-2">
                <button class="btn btn-primary dropdown-toggle mb-2" type="button" id="dropdownMenuButton"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Lainya
                </button>
                <div class="dropdown-menu mr-2" x-placement="bottom-start"
                  style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                  <a href="#modal__edit" data-toggle="modal"
                    onclick="$('#modal__edit #form__edit').attr('action', '/admin/siswa/{{ $siswa->id }}/update');$('#modal__edit #form__edit #card_id').attr('value', '{{ $siswa->card_id }}');$('#modal__edit #form__edit #nis').attr('value', '{{ $siswa->nis }}');$('#modal__edit #form__edit #nama').attr('value', '{{ $siswa->nama }}');$('#modal__edit #form__edit #kelas').attr('value', '{{ $siswa->kelas }}');$('#modal__edit #form__edit #foto_sekarang').attr('src', '{{ asset('images/siswa/' . $siswa->foto) }}');"
                    class="dropdown-item text-warning font-weight-bolder">Ubah</a>
                  <a href="#modal__delete" data-toggle="modal"
                    onclick="$('#modal__delete #form__delete').attr('action', '/admin/siswa/{{ $siswa->id }}/destroy');$('#modal__delete #form__delete #card_id').text('{{ $siswa->card_id }}');$('#modal__delete #form__delete #nis').text('{{ $siswa->nis }}');$('#modal__delete #form__delete #nama').text('{{ $siswa->nama }}');$('#modal__delete #form__delete #kelas').text('{{ $siswa->kelas }}');$('#modal__delete #form__delete #foto').attr('src', '{{ asset('images/siswa/' . $siswa->foto) }}');"
                    class="dropdown-item text-danger font-weight-bolder">Hapus</a>
                </div>
              </div>
            </td>
          </tr>
          <?php $count++ ?>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@section('modal')

<!-- Modal Create -->
<div class="modal fade" id="modal__create" data-backdrop="static" data-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.siswa.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Tambah Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="card_id">Card ID<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('card_id') is-invalid @enderror" id="card_id"
              name="card_id">
            @error('card_id')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="nis">NIS<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis">
            @error('nis')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="nama">Nama<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama">
            @error('nama')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="kelas">Kelas<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('kelas') is-invalid @enderror" id="kelas"
              name="kelas">
            @error('kelas')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="foto">Foto<span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" required>
            @error('foto')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="modal__edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form__edit" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Ubah Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="card_id">Card ID<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('card_id') is-invalid @enderror" id="card_id"
              name="card_id" value="">
            @error('card_id')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="nis">NIS<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
              value="">
            @error('nis')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="nama">Nama<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="">
            @error('nama')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="kelas">Kelas<span class="text-danger">*</span></label>
            <input type="text" required class="form-control @error('kelas') is-invalid @enderror" id="kelas"
              name="kelas" value="">
            @error('kelas')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto">
            @error('foto')
            <div class="invalid-feedback">
              {{ $message}}
            </div>
            @enderror
          </div>
          <div class="d-flex justify-content-center">
            <img src="" class="rounded" id="foto_sekarang" height="200px" width="150px" alt="..."
              style="object-fit: cover;border: 2px solid #191d21">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal__delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form__delete" action="" method="get">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Yakin menghapus Siswa?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group m-0">
            <label for="nama">Card ID</label>
            <p id="card_id" class="text-primary"></p>
          </div>
          <div class="form-group m-0">
            <label for="nis">NIS</label>
            <p id="nis" class="text-primary"></p>
          </div>
          <div class="form-group m-0">
            <label for="nama">Nama</label>
            <p id="nama" class="text-primary"></p>
          </div>
          <div class="form-group m-0">
            <label for="kelas">Kelas</label>
            <p id="kelas" class="text-primary"></p>
          </div>
          <div class="form-group m-0">
            <label for="foto">Foto</label>
            <div class="d-flex justify-content-center">
              <img src="" class="rounded" id="foto" height="200px" width="150px" alt="..."
                style="object-fit: cover;border: 2px solid #191d21">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-danger ml-2">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection