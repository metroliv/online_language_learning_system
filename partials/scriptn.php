<!-- jQuery -->
<script src="../public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Alerts -->
<script src="../public/plugins/sweetalert2/sweetalert2.js"></script>
<!-- ChartJS -->
<script src="../public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../public/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../public/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../public/plugins/moment/moment.min.js"></script>
<script src="../public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../public/dist/js/adminlte.js"></script>
<!-- AdminLTE App -->
<!-- <script src="../public/js/adminlte.js"></script> -->

<script src="../public/js/pages/dashboard.js"></script>

<!-- new -->

<!-- DataTables  & Plugins -->
<script src="../public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../public/plugins/jszip/jszip.min.js"></script>
<script src="../public/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../public/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../public/plugins/jszip/jszip.min.js"></script>
<script src="../public/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../public/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<!-- Select2 -->
<script src="../public/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../public/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="../public/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- bootstrap color picker -->
<script src="../public/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      destroy: true,
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "pageLength": 25,
      "lengthMenu": [10, 25, 50, 100], 
      "buttons": [
          {
            extend: 'pdf',
            text: 'Export PDF',
            title: 'Revenue Collection Reporting Tool', // Custom PDF title
            exportOptions: {
              columns: ':visible' // Export only visible columns
            },
            customize: function (doc) {
              // Customize PDF header and table structure
              doc.content[0].text = 'County Treasury Revenue Collection Reporting Tool';
              doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
            }
          },
          {
            extend: 'excel',
            text: 'Export Excel',
            title: 'Revenue Collection Reporting Tool'
          },
          {
            extend: 'print',
            text: 'Print View',
            title: 'Revenue Collection Reporting Tool',
            customize: function (win) {
              $(win.document.body).css('font-size', '10pt').prepend('<h3>Revenue Collection Reporting Tool</h3>');
            }
          }
        ],
      
        // Additional options
        "searching": true, // Enable searching
        "ordering": true,  // Enable column sorting
        "info": true,      // Show info (e.g., "Showing 1 to 10 of 50 entries")

        // Language customization
        "language": {
          "search": "Quick Search:", // Customize search input label
          "paginate": {
            "previous": "Prev",
            "next": "Next"
          },
          "lengthMenu": "Show _MENU_ entries",
          "info": "Displaying _START_ to _END_ of _TOTAL_ entries"
        }
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

  });
  $(document).ready(function () {
    $('.data_table').DataTable({
      "pageLength": 50
    }).css('white-space', 'initial');
  });
</script>

<!-- Swal-->
<script>
  /* Prevent double submissions */
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>




<!-- Alerts -->
<?php
include('alert.php');
require_once('../partials/logout.php');
?>