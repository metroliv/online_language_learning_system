

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
 <!-- Alerts -->
 <?php include('alert.php'); ?>
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
 <script src="../public/js/adminlte.js"></script>
 <script src="../public/dist/js/adminlte.js"></script>

 
 <script src="../public/js/pages/dashboard.js"></script>
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



<!-- Select2 -->
<script src="../public/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../public/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

<script src="../public/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- bootstrap color picker -->
<script src="../public/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

<!--  -->
<!-- Bootstrap Switch -->
<script src="../public/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../public/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../public/plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../public/dist/js/adminlte.min.js"></script>
<!--  -->


<!-- AdminLTE for demo purposes -->
<!-- <script src="../public/dist/js/demo.js"></script> -->
<!-- Page specific script - datatables -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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
</script>
 <!-- Swal-->  
 <script>
     /* Prevent double submissions */
     if (window.history.replaceState) {
         window.history.replaceState(null, null, window.location.href);
     }
 </script>

<!-- Page specific script -->
<script>
  $(function () {
    /* jQueryKnob */

    $('.knob').knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a   = this.angle(this.cv)  // Angle
            ,
              sa  = this.startAngle          // Previous start angle
            ,
              sat = this.startAngle         // Start angle
            ,
              ea                            // Previous end angle
            ,
              eat = sat + a                 // End angle
            ,
              r   = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    })
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    var sparkline1 = new Sparkline($('#sparkline-1')[0], { width: 240, height: 70, lineColor: '#92c1dc', endColor: '#92c1dc' })
    var sparkline2 = new Sparkline($('#sparkline-2')[0], { width: 240, height: 70, lineColor: '#f56954', endColor: '#f56954' })
    var sparkline3 = new Sparkline($('#sparkline-3')[0], { width: 240, height: 70, lineColor: '#3af221', endColor: '#3af221' })

    sparkline1.draw([1000, 1200, 920, 927, 931, 1027, 819, 930, 1021])
    sparkline2.draw([515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921])
    sparkline3.draw([15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21])

  })

</script>
 <!-- ./ inline html -->

 <?php
include('alert.php');
require_once('../partials/logout.php'); ?>