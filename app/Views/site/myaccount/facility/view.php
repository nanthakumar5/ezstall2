<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<div class="page-action mb-4 m-0" align="left">
	<a href="<?php echo base_url(); ?>/myaccount/facility" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $detail['image']?>" width="100%" height="auto" class="rounded">
					</div>
					<div class="col-md-12 mt-3">
						<h4 class="checkout-fw-6"><?php echo $detail['name'] ?></h4>
						<p><?php echo $detail['description'] ?></p>
					</div>
				</div>
			</div>
			<?php 
				$tabbtn = '';
				$tabcontent = '';
				foreach ($detail['barn'] as $barnkey => $barndata) {
					$barnid = $barndata['id'];
					$barnname = $barndata['name'];
					$barnactive = $barnkey=='0' ? ' show active' : '';
					$tabbtn .= '<button class="nav-link'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';
				
					$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
										<ul class="list-group">';
						foreach($barndata['stall'] as $stalldata){
							$bookedstalldata = [];
							if (!empty($stalldata['bookedstall'])) {
								foreach($stalldata['bookedstall'] as $bookedstall){
									if($bookedstall['status']=='1'){
										$bookedstalldata[] ='<div class="col-custom-3 p-2 border rounded ad-stall-base mx-2">
																<table>
																	<tr>
																		<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Name</p></td>
																		<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.$bookedstall['name'].'</p></td>
																	</tr>
																	<tr>
																		<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Date</p></td>
																		<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.formatdate($bookedstall['check_in'], 1).' to '.formatdate($bookedstall['check_out'], 1).'</p></td>
																	</tr>
																</table>
															</div>
															';
								}
								}
							}
								$tabcontent .= 	'<li class="list-group-item px-4 py-3">
													<p class="text-bold mb-1">
													'.$stalldata['name'].'<div class="row">'.implode('', $bookedstalldata).'</div>
													</p>
												</li>';
					}
						$tabcontent .= '</ul></div>';
				}
				
				$rvtabbtn = '';
				$rvtabcontent = '';
				foreach ($detail['rvbarn'] as $rvbarnkey => $rvbarndata) {
					$rvbarnid = $rvbarndata['id'];
					$rvbarnname = $rvbarndata['name'];
					$rvbarnactive = $rvbarnkey=='0' ? ' show active' : '';
					$rvtabbtn .= '<button class="nav-link'.$rvbarnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$rvbarnid.'" type="button" role="tab" aria-controls="barn'.$rvbarnid.'" aria-selected="true">'.$rvbarnname.'</button>';
				
					$rvtabcontent .= '<div class="tab-pane fade'.$rvbarnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
										<ul class="list-group">';
						foreach($rvbarndata['rvstall'] as $rvstalldata){
							$rvbookedstalldata = [];
							if (!empty($rvstalldata['rvbookedstall'])) {
								foreach($rvstalldata['rvbookedstall'] as $rvbookedstall){
									if($rvbookedstall['status']=='1'){
										$rvbookedstalldata[] ='<div class="col-custom-3 p-2 border rounded ad-stall-base mx-2">
																<table>
																	<tr>
																		<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Name</p></td>
																		<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.$rvbookedstall['name'].'</p></td>
																	</tr>
																	<tr>
																		<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Date</p></td>
																		<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.formatdate($rvbookedstall['check_in'], 1).' to '.formatdate($rvbookedstall['check_out'], 1).'</p></td>
																	</tr>
																</table>
															</div>
															';
								}
								}
							}
								$rvtabcontent .= 	'<li class="list-group-item px-4 py-3">
													<p class="text-bold mb-1">
													'.$rvstalldata['name'].'<div class="row">'.implode('', $rvbookedstalldata).'</div>
													</p>
												</li>';
					}
						$rvtabcontent .= '</ul></div>';
				}
			?>
			<div class="barn-nav mt-4">
			<nav>
					<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
						<?php echo $tabbtn; ?>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<?php echo $tabcontent; ?>
				</div>    
			</div>
			<div class="rvbarn-nav mt-4">
				<nav>
					<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
						<?php echo $rvtabbtn; ?>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<?php echo $rvtabcontent; ?>
				</div>    
			</div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>