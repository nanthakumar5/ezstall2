<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="page-action mb-4 m-0" align="right">
	<a href="<?php echo base_url(); ?>/myaccount/facility" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<h2 class="fw-bold">Current Inventories</h2>
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row mb-3">
					<table class="table-hover table-striped table-light table">
						<h5 class="fw-bold text-muted">Feed</h5>
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Product Quantity</th>
								<th>Product Price</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($product)){
								foreach ($product as $products) { 
									if($products['type']=='1'){ ?>
										<tr>
											<td><?php echo $products['name']?></td>
											<td><?php echo $products['quantity']?></td>
											<td><?php echo $products['price']?></td>
										</tr>
								<?php } } ?>
							<?php } else { ?>
								<tr><td colspan="3">No Feed Inventories</td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row">
					<table class="table-hover table-striped table-light table">
						<h5 class="fw-bold text-muted">Shavings</h5>
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Product Quantity</th>
								<th>Product Price</th>
							</tr>
						</thead>
						<tbody>

						<?php if(!empty($product)){
							foreach ($product as $products) { 
								if($products['type']=='2'){ ?>
									<tr>
										<td><?php echo $products['name']?></td>
										<td><?php echo $products['quantity']?></td>
										<td><?php echo $products['price']?></td>
									</tr>
							<?php } } ?>
							<?php } else { ?>
									<tr><td colspan="3">No Shavings Inventories</td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>