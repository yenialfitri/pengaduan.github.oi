<!-- Begin Page Content -->
 <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

<div class="card shadow">
  <div class="card-header">
    Tulis pengaduan
  </div>
  <div class="card-body">
    <?= $this->session->flashdata('message'); ?>
    <form action="<?= base_url('user/pengaduan_masyarakat'); ?>" method="post">
       <div class="form-group cols-sm-6">
          <label>Judul Laporan</label>
            <input type="text" class="form-control" id="jdl_laporan" name="tgl_pengaduan" >
        </div>

      <div class="form-group cols-sm-6">
          <label>Tanggal pengaduan</label>
            <input type="text" class="form-control" id="tgl_pengaduan" name="tgl_pengaduan" >
        </div>

        <div class="form-group cols-sm-6">
          <label>Tulis laporan</label>
          <textarea class="form-control" rows="7" name="isi_laporan" placeholder="Tulis Laporan Anda"></textarea>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary">kirim</button>
        </div>
    </form>
  </div>
</div>
                    

 </div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

            
