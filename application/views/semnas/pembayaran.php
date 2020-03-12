<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $c_title; ?></h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <div class="row justify-content-center">
        <div class="flash-message" data-title="Verifikasi Pembayaran" data-message="<?= $this->session->flashdata('message'); ?>"></div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped data-tables">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Asal Instansi</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach($data_bayar as $row) : ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $row['kode'] . " - " . $row['nama_lengkap']; ?></td>
                                        <td><?= $row['asal_instansi']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td><?= $row['no_telp']; ?></td>
                                        <td>
                                            <center>
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <form action="<?= base_url('admin/verifikasipembayaran'); ?>" method="post">
                                                    <input type="hidden" name="kode" value="<?= $row['kode']; ?>">
                                                    <input type="hidden" name="email" value="<?= $row['email']; ?>">
                                                    <button type="submit" class="btn btn-primary btn-xs">Verifikasi</button>
                                                </form>
                                            </center>
                                        </td>
                                    </tr>
                                    <?php $no++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

