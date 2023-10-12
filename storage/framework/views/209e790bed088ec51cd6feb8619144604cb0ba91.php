<?php 
	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

	$items = $data["_search_item_id"];
	$_qtys = $data["_qty"];
	$_barcodes = $data["_barcode"];
	$_sales_rates = $data["_sales_rate"];
	$_vats = $data["_vat"];
	$_discounts = $data["_discount"];
	$_product_name_check = $data["_product_name_check"] ?? '';
	$_product_price_check = $data["_product_price_check"] ?? '';
	$_bussiness_name = $data["_bussiness_name"] ?? '';
	$_vat_check = $data["_vat_check"] ?? '';
	$_discount_check = $data["_discount_check"] ?? '';
	$_product_name_size = $data["_product_name_size"] ?? 14;
	$_product_price_size = $data["_product_price_size"] ?? 14;
	$_bussiness_name_size = $data["_bussiness_name_size"] ?? 14;
	$_vat_size = $data["_vat_size"] ?? 14;
	$_discount_size = $data["_discount_size"] ?? 14;
	?>
	<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
	<?php
	$_qty = $_qtys[$key];
	$_loop_time = $_qty;
	?>
	<?php for($i=0; $i<$_loop_time; $i++): ?>



<table align="center" style="border-spacing: 0in 0in; overflow: hidden !important;">
	<!-- create a new row -->
	<tr>
		<!-- <columns column-count="1" column-gap="0"> -->
		<td align="center" valign="center">
			<div style="overflow: hidden !important;display: flex; flex-wrap: wrap;align-content: center;width: 1.25in; height: 1in; justify-content: center;">
				<div> 
					 <?php if($_bussiness_name !=''): ?> <b style="display: block !important; font-size: <?php echo e($_bussiness_name_size); ?>px"><?php echo e($settings->name ?? ''); ?></b> <?php endif; ?> 
					<?php if($_product_name_check !=''): ?><span style="display: block !important; font-size: 15px"><?php echo e($items[$key]); ?> </span>  <?php endif; ?>
					<?php if($_product_price_check !=''): ?><span style="font-size: <?php echo e($_product_price_size); ?>px;">Price:<b><?php echo e(prefix_taka()); ?>.<?php echo e($_sales_rates[$key]); ?></b></span>  <?php endif; ?>
					<?php if($_vat_check !=''): ?><span style="font-size: <?php echo e($_vat_size); ?>px;">VAT:<b><?php echo e($_vats[$key]); ?></b></span> <?php endif; ?>
					<?php if($_discount_check !=''): ?><span style="font-size: <?php echo e($_discount_size); ?>px;">Discount:<b><?php echo e($_discounts[$key]); ?></b></span> <?php endif; ?>
					<br> <?php echo  '<img style="max-width:90% !important;height: 0.24in !important; display: block;;"  src="data:image/png;base64,' . base64_encode($generator->getBarcode('"'.$_barcodes[$key].'"', $generator::TYPE_CODE_128)) . '">'; ?>
					 <span style="font-size: 10px !important"><?php echo e($_barcodes[$key]); ?></span> 

					
				</div>
			</div>
		</td>
	</tr>
</table>
<style type="text/css">
td {
	border: 1px dotted lightgray;
}

@media  print {
	table {
		page-break-after: always;
	}
	@page  {
		size: 1.25in 1in;
		/*width: 1.25in !important;*/
		/*height:1in !important ;*/
		margin-top: 0in !important;
		margin-bottom: 0in !important;
		margin-left: 0in !important;
		margin-right: 0in !important;
	}
}
</style>
<?php endfor; ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
	<?php endif; ?>

<script>
window.print()
</script><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/final_print_6.blade.php ENDPATH**/ ?>