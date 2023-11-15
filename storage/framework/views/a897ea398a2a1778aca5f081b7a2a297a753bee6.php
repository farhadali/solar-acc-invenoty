
<?php $__env->startSection('title',$page_name ?? ''); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>

    <div class="content">
      <div class="container-fluid">
   <h2 class="text-center"><?php echo $page_name ?? ''; ?></h2>
    <div class="container-fluid   " >
        <div class="row  ">
                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-report-menu')): ?> 
                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4><?php echo e(__('label.account_report')); ?></h4>
                    <ul>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('day-book')): ?>
                        <li><a target="__blank" href="<?php echo e(url('day-book')); ?>"><?php echo e(__('label.Day Book')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-book')): ?>
                        <li><a target="__blank" href="<?php echo e(url('bank-book')); ?>"><?php echo e(__('label.Bank Book')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('receipt-payment')): ?>
                        <li><a target="__blank" href="<?php echo e(url('receipt-payment')); ?>"><?php echo e(__('label.Receipt & Payment')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ledger-report')): ?>
                        <li><a target="__blank" href="<?php echo e(url('ledger-report')); ?>"><?php echo e(__('label.Ledger Report')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('group-ledger')): ?>
                        <li><a target="__blank" href="<?php echo e(url('group-ledger')); ?>"><?php echo e(__('label.Group Ledger Report')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ledger-summary-report')): ?>
                        <li><a target="__blank" href="<?php echo e(url('ledger-summary-report')); ?>"><?php echo e(__('label.Ledger Summary Report')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('trail-balance')): ?>
                        <li><a target="__blank" href="<?php echo e(url('trail-balance')); ?>"><?php echo e(__('label.Trail Balance')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('work-sheet')): ?>
                        <li><a target="__blank" href="<?php echo e(url('work-sheet')); ?>"><?php echo e(__('label.Work Sheet')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('balance-sheet')): ?>
                        <li><a target="__blank" href="<?php echo e(url('balance-sheet')); ?>"><?php echo e(__('label.Balance Sheet')); ?></a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chart-of-account')): ?>
                        <li><a target="__blank" href="<?php echo e(url('chart-of-account')); ?>"><?php echo e(__('label.Chart of Account')); ?></a></li>
                        <?php endif; ?>
                    </ul>
                   </div>
                </div>
                <?php endif; ?>
                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory-report')): ?> 
                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4><?php echo e(__('label.inventory_report')); ?></h4>
                    <ul>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-check')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('warranty-check')); ?>" > <?php echo e(__('label.warranty-check')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bill-party-statement')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('bill-party-statement')); ?>" >  <?php echo e(__('label.Bill of Supplier Statement')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-purchase')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('date-wise-purchase')); ?>" >  <?php echo e(__('label.Date Wise Purchase')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-detail')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('purchase-return-detail')); ?>" >  <?php echo e(__('label.Purchase Return Detail')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-sales')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('date-wise-sales')); ?>" ><?php echo e(__('label.Date Wise Sales')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-detail')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('sales-return-detail')); ?>" ><?php echo e(__('label.Sales Return Details')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-possition')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('stock-possition')); ?>" ><?php echo e(__('label.Stock Possition')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-ledger')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('single-stock-ledger')); ?>" ><?php echo e(__('label.single-stock-ledger')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-ledger')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('stock-ledger')); ?>" ><?php echo e(__('label.Stock Ledger')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-value')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('stock-value')); ?>" ><?php echo e(__('label.Stock Value')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-value-register')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('stock-value-register')); ?>" ><?php echo e(__('label.Stock Value Register')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('gross-profit')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('gross-profit')); ?>" ><?php echo e(__('label.Gross Profit')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expired-item')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('expired-item')); ?>" ><?php echo e(__('label.Expired Item')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('shortage-item')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('shortage-item')); ?>" ><?php echo e(__('label.Shortage Item')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('barcode-history')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('barcode-history')); ?>" ><?php echo e(__('label.Barcode History')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-wise-collection-payment')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('user-wise-collection-payment')); ?>" ><?php echo e(__('label.User Wise Collection Payment')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-invoice-print')): ?>
                         <li>
                            <a target="__blank" href="<?php echo e(url('date-wise-invoice-print')); ?>" ><?php echo e(__('label.Date Wise Invoice Print')); ?>

                                  </a>
                          </li>
                        <?php endif; ?>
                    </ul>
                   </div>
                </div>
                <?php endif; ?>

                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4><?php echo e(__('label._import_report')); ?></h4>
                    <ul>
                        <li><a target="__blank" href="<?php echo e(url('master_vessel_wise_ligther_report')); ?>"><?php echo e(__('label.master_vessel_wise_ligther_report')); ?></a></li>
                    </ul>
                   </div>
                </div>
                
        </div>
    </div>
    
</div>
    
    </div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/report-panel/dashboard.blade.php ENDPATH**/ ?>