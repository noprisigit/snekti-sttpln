        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.1
            </div>
            <strong>Copyright &copy; 2020 <a href="<?= base_url('admin'); ?>">SNEKTI</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets2/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>assets2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?= base_url(); ?>assets2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>assets2/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url(); ?>assets2/dist/js/demo.js"></script>
    <!-- Script JS -->
    <script src="<?= base_url(); ?>assets2/script.js"></script>
    <!-- Main JS -->
    <?php foreach($file_js as $js) : ?>
    <script src="<?= base_url() . $js; ?>"></script>
    <?php endforeach; ?>

    <script>
        $(function () {
            $(".data-tables").DataTable({

            });
            
            $(".data-paper").DataTable({
				scrollX:        true,
				scrollCollapse: true,
				autoWidth:         true,  
				 paging:         true,       
				columnDefs: [
				{ "width": "10px", "targets": 0 },
				{ "width": "200px", "targets": [1,2] },       
				{ "width": "40px", "targets": [4] }
			  ]
            });
        });
    </script>
</body>

</html>