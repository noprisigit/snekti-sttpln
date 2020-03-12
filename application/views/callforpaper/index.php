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
        <div class="flash-message" data-title="Data Peserta Semnas" data-message="<?= $this->session->flashdata('message'); ?>"></div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped data-paper table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Tim</th>
                                        <th>Nama Penulis</th>
                                        <th>Sub Tema</th>
                                        <th>Institusi</th>
                                        <th>Status</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Nama File</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach($data_makalah as $row) : ?>
                                    <tr>
                                        <td><center><?= $no; ?></center></td>
                                        <td><?= $row['judul_tim']; ?></td>
                                        <td><?= $row['nama_penulis']; ?></td>
                                        <td><?= $row['sub_tema']; ?></td>
                                        <td><?= $row['institusi']; ?></td>
                                        <td><?= $row['status']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td><?= $row['no_telp']; ?></td>
                                        <td><?= $row['nama_file']; ?></td>
                                        <td>
                                            <center>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admin/downloadmakalah/?file=') . $row['nama_file']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Download"><i class="fas fa-download"></i></a>
                                                </div>
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