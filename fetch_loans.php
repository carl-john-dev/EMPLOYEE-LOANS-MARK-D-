<?php
include 'connection.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'active';

if($type == 'active') {
    $where = "l.deleted_at IS NULL";
} elseif($type == 'archived') {
    $where = "l.deleted_at IS NOT NULL";
} else {
    $where = "1=1";
}

$sql = "SELECT l.*, e.emp_name, e.SSS_ID, e.pagibig_id 
        FROM tbl_employee_loans l
        LEFT JOIN tbl_employee e ON l.emp_id = e.emp_id
        WHERE $where
        ORDER BY l.loan_id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $statusClass = ($row['status'] == 'Paid') ? 'badge-paid' : 'badge-unpaid';
        $isArchived = $row['deleted_at'] !== null;
        ?>
        <tr data-loan-id="<?php echo $row['loan_id']; ?>">
            <td><?php echo htmlspecialchars($row['loan_id']); ?></td>
            <td><?php echo htmlspecialchars($row['emp_name']); ?></td>
            <td><?php echo htmlspecialchars($row['dept']); ?></td>
            <td><?php echo htmlspecialchars($row['SSS_ID']); ?></td>
            <td><?php echo htmlspecialchars($row['pagibig_id']); ?></td>
            <td><?php echo date('M d, Y', strtotime($row['date_applied'])); ?></td>
            <td><span class="badge bg-info"><?php echo htmlspecialchars($row['loan_type']); ?></span></td>
            <td>₱<?php echo number_format($row['loan_amount'], 2); ?></td>
            <td>₱<?php echo number_format($row['total_deduction'], 2); ?></td>
            <td><?php echo date('M d, Y', strtotime($row['deduction_start'])); ?></td>
            <td><?php echo $row['payment_1yr'] ? date('M d, Y', strtotime($row['payment_1yr'])) : 'N/A'; ?></td>
            <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-info me-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#viewModal"
                        onclick="viewLoan(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                    <i class="bi bi-eye"></i>
                </button>
                <?php if(!$isArchived): ?>
                    <a href="edit_loan.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-warning" onclick="archiveLoan(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                        <i class="bi bi-archive"></i> Archive
                    </button>
                <?php else: ?>
                    <button class="btn btn-sm btn-outline-success" onclick="restoreLoan(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deletePermanently(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="13" class="text-center py-4">📭 No records found</td>
    </tr>
    <?php
}
?>