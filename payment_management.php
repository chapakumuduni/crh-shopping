<?php
session_start();
require_once('db.php');
require_once('vendor/autoload.php'); // Ensure Dompdf is installed

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Fetch payment transactions
$payments_query = "SELECT * FROM payments";
$payments_result = $conn->query($payments_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['refund_payment'])) {
    $payment_id = $_POST['payment_id'];
    $update_query = "UPDATE payments SET status = 'Refunded' WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $payment_id);
    
    if ($stmt->execute()) {
        $message = "Payment refunded successfully!";
    } else {
        $message = "Error processing refund.";
    }
    $stmt->close();
}

// Generate PDF bill
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_pdf'])) {
    $dompdf = new Dompdf(new Options(['isHtml5ParserEnabled' => true]));
    $html = '<h2>Payment Transactions</h2><table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr><th>Transaction ID</th><th>User ID</th><th>Amount</th><th>Status</th><th>Date</th></tr>';
    
    $payments_query = "SELECT * FROM payments";
    $payments_result = $conn->query($payments_query);
    while ($payment = $payments_result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$payment['id']}</td>
                    <td>{$payment['user_id']}</td>
                    <td>\${$payment['amount']}</td>
                    <td>{$payment['status']}</td>
                    <td>{$payment['created_at']}</td>
                  </tr>";
    }
    $html .= "</table>";
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("payment_transactions.pdf", ["Attachment" => false]);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Payment Management</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-info"> <?php echo $message; ?> </div>
        <?php endif; ?>
        
        <div class="d-flex mb-3">
            <a href="dashboard.php" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <form method="POST" class="d-inline">
                <button type="submit" name="generate_pdf" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Generate PDF Bill</button>
            </form>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = $payments_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $payment['id']; ?></td>
                        <td><?php echo $payment['user_id']; ?></td>
                        <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                        <td><?php echo $payment['status']; ?></td>
                        <td><?php echo $payment['created_at']; ?></td>
                        <td>
                            <?php if ($payment['status'] == 'Failed' || $payment['status'] == 'Pending') { ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                    <button type="submit" name="refund_payment" class="btn btn-warning btn-sm">Refund</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
