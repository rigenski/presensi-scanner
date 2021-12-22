@extends('layouts.admin')
@section('nav__item-admin-presensi', 'active')

@section('title', 'Presensi')

@section('content')
<div class="card mb-4">
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 mb-2">
      <div class="d-flex align-items-start">
        <a href="#modal__presensi" data-toggle="modal" class="btn btn-primary mr-2">
          Cetak Presensi
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
    <div class="row">
      <div class="col-md-4 col-sm-8 col-12">
        <div class="card">
          <div class="card-header">
            <h4>Persentase Hari Ini</h4>
          </div>
          <div class="card-body">
            <canvas id="chart_body" width="100" height="100"></canvas>
          </div>
          <div id="chart_desc" class="card-footer d-flex justify-content-between p-0 px-4">
            <p>Presensi Keluar: <b>0</b></p>
            <p>Presensi Masuk: <b>0</b></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('modal')

<!-- Modal Print -->
<div class="modal fade" id="modal__presensi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form__presensi" action="{{ route('admin.presensi.print') }}" method="get">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Yakin mencetak Presensi?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-primary ml-2">Yakin</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  const data = <?= $data_presensi ?>;

  let presensiMasuk = 0;
  let presensiPulang = 0;

  const setData = () => {
    data.map((x, i) => {
      if(x.kategori == 'masuk') {
        presensiMasuk += 1;
      } else {
        presensiPulang += 1;
      }
    })
  }

  window.onload = setData();

  const elDoughnutChart = document.getElementById('chart_body');

  const presensiDay = {
        labels: [
            "Presensi Masuk",
            "Presensi Pulang",
        ],
        datasets: [
            {
                data: [presensiMasuk, presensiPulang],
                backgroundColor: [
                    "#63ed7a",
                    "#fc544b",
                ]
            }]
    };

    const doughnutChart = new Chart(elDoughnutChart, {
        type: 'pie',
        data: presensiDay
    });

    const elCartDesc = document.getElementById('chart_desc');

    elCartDesc.innerHTML = `<p>Presensi Masuk: <b>${presensiPulang}</b></p><p>Presensi Pulang: <b>${presensiMasuk}</b></p>`
</script>
@endsection