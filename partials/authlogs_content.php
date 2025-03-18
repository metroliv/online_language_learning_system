<div class="card-body">
    <!-- filters -->
    <div class="container-fluid pb-2">
        <div class="col-md-3 col-sm-3 col-3"> 

        <div class="text-right">
            <a data-toggle="modal" data-target="#filterDashboard"><button type="button"
                    class="btn btn-outline-success btn-sm">filter</button></a>
        </div> 
        </div>
    </div>
    <!-- ./ filters -->
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Log</th>
                <th>User </th>
                <th>IP Address</th>
                <th>Device</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('tomorrow'));
            $fetch_records_sql = mysqli_query(
                $mysqli,
                "SELECT * FROM logs l
								INNER JOIN users u ON u.user_id = l.log_user_id
								WHERE u.user_id = '{$user_id}' AND
								l.log_date BETWEEN '{$today}' AND '{$tomorrow}' ORDER BY l.log_id DESC"
            );
            $cnt = 1;
            if (mysqli_num_rows($fetch_records_sql) > 0) {
                while ($return_results = mysqli_fetch_array($fetch_records_sql)) {
                    ?>
                    <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $return_results['user_names']; ?></td>
                        <td><?php echo $return_results['log_ip_address']; ?></td>
                        <td><?php echo $return_results['log_device']; ?></td>
                        <td><?php echo date('g:ia', strtotime($return_results['log_date'])); ?></td>
                    </tr>
                    <?php
                    $cnt = $cnt + 1;
                }
            } ?>

        </tbody>
    </table>
</div>