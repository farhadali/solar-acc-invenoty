<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">
                <div class="">
                    <div class=" ">
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Project Name: <?php echo $data->_master_cost_center->_name ?? ''; ?></strong>
                                <p><strong>[DRAFT]</strong></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <strong>Date: <?php echo _view_date_formate($data->created_at ?? ''); ?></strong>
                                <table class="table table-bordered">
                                     <tr>
                                    <td><strong>Project Value</strong></td>
                                    <td><strong><?php echo _report_amount($data->_project_value ?? 0); ?></strong></td>
                                </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                    <div class="">
                        
                        <table class=" table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">SL</th>
                                    <th class="text-center" scope="col">PARTICULARS</th>
                                    <th class="text-center" scope="col">QUANTITY</th>
                                    <th class="text-center" scope="col">UNIT COST</th>
                                    <th class="text-center" scope="col">TOTAL COST BDT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $_budget_item_details = $data->_budget_item_details ?? [];
                                $total_qty=0;
                                $total_item_amount=0;
                                ?>

                                <?php $__empty_1 = true; $__currentLoopData = $_budget_item_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                <?php
                                $total_qty +=$item_val->_item_qty ?? 0;
                                $total_item_amount +=$item_val->_item_budget_amount ?? 0;
                                ?>
                                <tr>
                                    <td class="text-center"><b><?php echo e(($key+1)); ?></b></td>
                                    <td><?php echo $item_val->_item->_item ?? ''; ?></b></td>
                                    <td class="text-right"><b><?php echo $item_val->_item_qty ?? 0; ?></b></td>
                                    <td class="text-right"><b><?php echo _report_amount($item_val->_item_unit_price ?? 0); ?></b></td>
                                    <td class="text-right"><b><?php echo _report_amount($item_val->_item_budget_amount ?? 0); ?></b></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"  class="text-center"><strong>TOTAL</strong></td>
                                    <td  class="text-right"><b><?php echo e(_report_amount($total_qty)); ?></b></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"><b><?php echo e(_report_amount($total_item_amount)); ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="width:100%;border-top: 1px solid #000;border-bottom: 1px solid #000;height: 5px;margin-top: 3px;"></div>
                        <div style="width:100%;height: 30px;"></div>

                        <div class="container">
                            <table style="width:100%;">
                                <thead>
                                    <tr style="border-bottom:1px solid #000;border-top: 1px solid #000;">
                                        <th>PARTICULARS</th>
                                        <th></th>
                                        <th class="text-center">BDT</th>
                                        <th class="text-center">BDT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_income=0;
                                    $_budget_details_income = $data->_budget_details_income ?? [];
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $_budget_details_income; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_w=>$in_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                     <?php
                                    $total_income +=$in_val->_budget_amount ?? 0;
                                    ?>
                                    <tr>
                                        <td><?php echo $in_val->_ledger->_name ?? ''; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b><?php echo _report_amount($in_val->_budget_amount ?? 0); ?></b></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="4">Less:</td>
                                    </tr>
                                    <?php
                                    $_budget_details_deduction = $data->_budget_details_deduction ?? [];
                                    $deduct_percentage=0;
                                    $deduct_amount=0;
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $_budget_details_deduction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d_key=>$d_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <?php
                                    $deduct_percentage +=(($d_val->_budget_amount/$total_income)*100);
                                    $deduct_amount +=$d_val->_budget_amount ?? 0;
                                    ?>
                                        <tr>
                                        <td><?php echo $d_val->_ledger->_name ?? ''; ?></td>
                                        <td class="text-right"><?php echo _report_amount(($d_val->_budget_amount/$total_income)*100); ?>%</td>
                                        <td class="text-right"><?php echo _report_amount($d_val->_budget_amount ?? 0); ?></td>
                                        <td></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    <tr style="border-bottom:1px solid #000;border-top: 1px solid #000;">
                                        <td><b>AVAILABLE FUND</b></td>
                                        <td class="text-right"><b><?php echo _report_amount($deduct_percentage); ?>%</b></td>
                                        <td class="text-right"><b><?php echo _report_amount($deduct_amount); ?></b></td>
                                        <td class="text-right"><b><?php echo _report_amount($total_income-$deduct_amount); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Less:</td>
                                    </tr>
                                    <tr>
                                        <?php
                                        $item_cost_percentage=($total_item_amount/$total_income)*10;
                                        ?>
                                        <td>PRODUCT COST & OPERATION</td>
                                        <td class="text-right"><?php echo _report_amount(($total_item_amount/$total_income)*100); ?>%</td>
                                        <td class="text-right"><?php echo _report_amount($total_item_amount); ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $_budget_details_expense = $data->_budget_details_expense ?? [];
                                    $total_parcentage=0;
                                    $total_expenses_amount=0;
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $_budget_details_expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e_key=>$e_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                    $total_parcentage +=(($e_val->_budget_amount/$total_income)*100);
                                    $total_expenses_amount +=$e_val->_budget_amount ?? 0;

                                    ?>
                                    <tr>
                                        <td><?php echo $e_val->_ledger->_name ?? ''; ?></td>
                                        <td class="text-right"><?php echo _report_amount(($e_val->_budget_amount/$total_income)*100); ?>%</td>
                                        <td class="text-right"><?php echo _report_amount($e_val->_budget_amount ?? 0); ?></td>
                                        <td></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    <tr style="border-bottom:1px solid #000;border-top: 1px solid #000;"><td colspan="4"></td></tr>
                                    
                                    <tr>
                                        <td><b>TOTAL OUTFLOW</b></td>
                                        <td class="text-right"><b><?php echo _report_amount($total_parcentage +$item_cost_percentage+$deduct_percentage); ?>%</b></td>
                                        <td class="text-right"><b><?php echo _report_amount($total_expenses_amount+$total_item_amount); ?></b></td>
                                        

                                        <td class="text-right"><b><?php echo _report_amount($total_expenses_amount+$total_item_amount+$deduct_amount); ?></b></td>
                                    </tr>
                                    <tr style="border-bottom:1px solid #000;border-top: 1px solid #000;"><td colspan="4"></td></tr>
                                     <tr>
                                        <td><b>PROFIT/LOSS</b></td>
                                        <td class="text-right"><b><?php echo _report_amount(100-($total_parcentage +$item_cost_percentage+$deduct_percentage)); ?>%</b></td>
                                        <td class="text-right"></td>
                                        
                                        <td class="text-right"><b><?php echo _report_amount($total_income-($total_expenses_amount+$total_item_amount+$deduct_amount)); ?></b></td>
                                    </tr>
                                    <tr style="border-bottom:1px solid #000;border-top: 1px solid #000;"><td colspan="4" style="height: 1px;"></td></tr>
                                     <tr>
                                </tbody>
                            </table>

                        </div>
                        <?php echo $data->_remarks ?? ''; ?>


                        <div class="row">
                            <?php
                            $budget_authorised_order = $data->budget_authorised_order ?? [];
                            $last_order= sizeof($budget_authorised_order);
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $budget_authorised_order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k_o=>$ao_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-4">
                                <div style="height:60px;width: 100%;"></div>
                                    <?php if($k_o==0): ?>
                                    <b>Prepared by:</b>
                                    <?php endif; ?>
                                    <?php if($k_o==($last_order-1)): ?>
                                    <b>Approved by:</b>
                                    <?php endif; ?><br>
                                    <?php echo $ao_val->erp_user_detail->office_id ?? ''; ?><br>
                                    <?php echo $ao_val->erp_user_detail->name ?? ''; ?><br>
                                    <?php echo $ao_val->erp_user_detail->_designation->name ?? ''; ?>

                               
                                
                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">
 window.print();

</script>
</body>
</html>


<?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/budgets/show.blade.php ENDPATH**/ ?>