<?php
require_once 'include/orderConf.php';
$order = new Orders();

$orders = $order->getOrder();  

echo "<script>var orders = " . json_encode($orders) . ";</script>";
?>

<div class="container-fluid">
    <h1 class="mb-3">Order List</h1>

    <div class="table-container">
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Ship To</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        
                    </tbody>
                </table>
            </div>
            <nav aria-label="Product Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
       
        const orders = <?php echo json_encode($orders); ?>;
    
        const tbody = document.getElementById("orderTableBody");
        orders.forEach(order => {
            const row = `<tr>
                <td><input type="checkbox"></td>
                <td>${order.order_number} by ${order.first_name} ${order.last_name}</td>
                <td>${order.order_date}</td>
                <td>${order.shipping_address}</td>
                <td><span class="status ${order.order_status.toLowerCase().replace(' ', '-')}">${order.order_status}</span></td>
                <td>$${order.total_amount}</td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle hide-arrow d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" >
                            <i class="bx bx-dots-vertical-rounded p-0"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Hold On</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Processing</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Completed</a>
                            <a class="dropdown-item" href="javascript:void(0);" style="color: red !important; "><i class="bx bx-trash me-1"></i> Delete</a>
                        </div>
                    </div>
                </td>
            </tr>`;
            tbody.innerHTML += row;
        });
    });
</script>
