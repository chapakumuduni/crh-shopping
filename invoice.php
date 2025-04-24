<?php
require 'vendor/autoload.php'; // Path to your dompdf autoload
require_once('db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['order_id'])) {
    exit("Order ID required.");
}

$order_id = intval($_GET['order_id']);

// Fetch order info
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

if (!$order) {
    exit("Order not found.");
}

// Fetch customer info
$customer_stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$customer_stmt->bind_param("i", $order['user_id']);
$customer_stmt->execute();
$customer = $customer_stmt->get_result()->fetch_assoc();
$customer_stmt->close();

// Fetch ordered products info (assuming a `order_products` table)
$product_stmt = $conn->prepare("SELECT op.*, p.name AS product_name, p.price FROM order_products op 
    LEFT JOIN products p ON op.product_id = p.id WHERE op.order_id = ?");
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$products_result = $product_stmt->get_result();
$product_stmt->close();

// Calculate the total price of the products (in case you want to display a breakdown)
$subtotal = 0;
while ($product = $products_result->fetch_assoc()) {
    $subtotal += $product['quantity'] * $product['price'];
}

// Assuming tax rate (you can change this to a dynamic value)
$tax_rate = 0.10; // 10% tax
$tax_amount = $subtotal * $tax_rate;
$shipping_cost = 10.00; // Flat shipping fee (you can change it dynamically)
$grand_total = $subtotal + $tax_amount + $shipping_cost;

$html = '
<style>
    body { font-family: sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 20px; }
    .company { font-size: 20px; font-weight: bold; color: #2c3e50; }
    .invoice-box {
        max-width: 100%;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        background: #fff;
    }
    .info-table, .items-table, .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .info-table td, .items-table td, .summary-table th, .summary-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .summary-table th {
        background-color: #f8f9fa;
        text-align: left;
    }
    .right { text-align: right; }
    .items-table th, .summary-table th { text-align: left; }
</style>

<div class="invoice-box">
    <div class="header">
        <div class="company">CR Shopping</div>
        <div>Invoice #'.$order['id'].'</div>
        <div>Date: '.date('M d, Y', strtotime($order['order_date'])).'</div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Customer Info:</strong><br>'.$customer['name'].'<br>'.$customer['email'].'<br>'.$customer['address'].'<br>'.$customer['phone'].'</td>
            <td><strong>Order Info:</strong><br>Order Status: '.$order['status'].'<br>Payment Method: '.$order['payment_method'].'</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th class="right">Unit Price</th>
                <th class="right">Quantity</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>';

        // Loop through products and display
        while ($product = $products_result->fetch_assoc()) {
            $product_total = $product['quantity'] * $product['price'];
            $html .= '
            <tr>
                <td>'.$product['product_name'].'</td>
                <td class="right">$'.number_format($product['price'], 2).'</td>
                <td class="right">'.$product['quantity'].'</td>
                <td class="right">$'.number_format($product_total, 2).'</td>
            </tr>';
        }

$html .= '
        </tbody>
    </table>

    <table class="summary-table">
        <thead>
            <tr>
                <th>Summary</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Subtotal</td>
                <td class="right">$'.number_format($subtotal, 2).'</td>
            </tr>
            <tr>
                <td>Tax (10%)</td>
                <td class="right">$'.number_format($tax_amount, 2).'</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td class="right">$'.number_format($shipping_cost, 2).'</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Grand Total</strong></td>
                <td class="right"><strong>$'.number_format($grand_total, 2).'</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("Invoice_{$order_id}.pdf", ["Attachment" => 0]); // Download = 1
?>
