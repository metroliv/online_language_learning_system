<div class="card-body">
    <!-- Filter Row -->
    <div class="row mb-3">
        <!-- Financial Year Selector -->
        <div class="col-md-3">
            <label for="fySelector">Financial Year</label>
            <select id="fySelector" class="form-control">
                <option value="2024/2025">2024/2025</option>
                <option value="2025/2026">2025/2026</option>
                <!-- Additional financial years can be added here -->
            </select>
        </div>

        <!-- Date Filter Selector -->
        <div class="col-md-3">
            <label for="dateFilter">Date Filter</label>
            <select id="dateFilter" class="form-control">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="annually">Annually</option>
                <option value="custom">Custom </option>
            </select>
        </div>

        <!-- Custom Date Range Pickers (Initially Hidden) -->
        <div class="col-md-6 d-none" id="customDateRange">
            <label>Custom Date Range</label>
            <div class="input-group">
                <input type="text" id="startDate" class="form-control" placeholder="Start Date">
                <input type="text" id="endDate" class="form-control" placeholder="End Date">
            </div>
        </div>
    </div>

    <!-- Collections Table -->
    <form id="collectionsForm" action="" method="POST">
        <table id="collectionsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ward</th>
                    <th>User</th>
                    <th>Stream</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Collection data will be populated here by DataTables -->
            </tbody>
        </table>
    </form>
</div>

<script>
$(document).ready(function () {
    // Initialize date picker for custom date range
    $('#startDate, #endDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    // Toggle custom date range inputs based on date filter selection
    $('#dateFilter').change(function () {
        if ($(this).val() === 'custom') {
            $('#customDateRange').removeClass('d-none');
        } else {
            $('#customDateRange').addClass('d-none');
        }
    });
});

$(document).ready(function () {
			// Initialize DataTable with server-side processing
			// const collectionsTable = $('#collectionsTable').DataTable({
			// 	serverSide: true,
			// 	processing: true,
                
			// 	ajax: {
			// 		url: '../helpers/temp.php',
			// 		type: 'POST',
			// 		data: function (d) {

			// 			console.log('Request Data:', {
			// 				fy: $('#fySelector').val(),
			// 				dateFilter: $('#dateFilter').val(),
			// 				startDate: $('#startDate').val(),
			// 				endDate: $('#endDate').val()
			// 			});
			// 			d.fy = $('#fySelector').val();  // Fiscal Year filter
			// 			d.dateFilter = $('#dateFilter').val();  // Date Filter (daily, weekly, monthly, custom)

			// 			// Only send start and end dates if 'custom' date filter is selected
			// 			if ($('#dateFilter').val() === 'custom') {
			// 				d.startDate = $('#startDate').val();
			// 				d.endDate = $('#endDate').val();
			// 			} else {
			// 				d.startDate = '';  // Empty or undefined if not custom date range
			// 				d.endDate = '';  // Empty or undefined if not custom date range
			// 			}
			// 		}
			// 	},
			// 	columns: [
			// 		{ data: 'collection_id', title: 'ID' },
			// 		{ data: 'ward_name', title: 'Ward' },
			// 		{ data: 'user_name', title: 'User' },
			// 		{ data: 'stream_name', title: 'Stream' },
			// 		{ data: 'service_name', title: 'Service' },
			// 		{ data: 'collection_amount', title: 'Amount' },
			// 		{ data: 'collection_date', title: 'Date' }
			// 	],
			// 	scrollY: '400px',
			// 	scrollCollapse: true,
			// 	lengthMenu: [50, 100, 300],
			// 	paging: true
			// });

			// Reload data when filters are changed
			$('#fySelector, #dateFilter, #startDate, #endDate').change(function () {
				collectionsTable.ajax.reload();
			});


			// Reload data when filters are changed
			$('#fySelector, #dateFilter, #startDate, #endDate').change(function () {
				collectionsTable.ajax.reload();
			});
		});
</script>