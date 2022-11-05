<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 				= isset($result['id']) ? $result['id'] : '';
		$name 				= isset($result['name']) ? $result['name'] : '';
		$description 		= isset($result['description']) ? $result['description'] : '';
		$address 			= isset($result['address']) ? $result['address'] : '';
		$email 		    	= isset($result['email']) ? $result['email'] : '';
		$phone 				= isset($result['phone']) ? $result['phone'] : '';
		$facebook 		    = isset($result['facebook']) ? $result['facebook'] : '';
		$google 			= isset($result['google']) ? $result['google'] : '';
		$twitter 		    = isset($result['twitter']) ? $result['twitter'] : '';
		$instagram 		    = isset($result['instagram']) ? $result['instagram'] : '';
		$logo 		    	= isset($result['logo']) ? $result['logo'] : '';
		$logo 				= filedata($logo, base_url().'/assets/uploads/settings/');
		$stripepublickey 	= isset($result['stripepublickey']) ? $result['stripepublickey'] : '';
		$stripeprivatekey 	= isset($result['stripeprivatekey']) ? $result['stripeprivatekey'] : '';
		$transactionfee 	= isset($result['transactionfee']) ? $result['transactionfee'] : '';
		$producereventfee 	= isset($result['producereventfee']) ? $result['producereventfee'] : '';
		$facilitystallfee 	= isset($result['facilitystallfee']) ? $result['facilitystallfee'] : '';
		$googleanalytics 	= isset($result['googleanalytics']) ? $result['googleanalytics'] : '';
		$sid 				= isset($result['sid']) ? $result['sid'] : '';
		$token 				= isset($result['token']) ? $result['token'] : '';
		$fromnumber			= isset($result['fromnumber']) ? $result['fromnumber'] : '';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Settings</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item active">Settings</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Settings</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form1" action="<?php echo getAdminUrl(); ?>/settings" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Name</label>								
									<input type="text" name="name" class="form-control" id="name" placeholder="Enter Title" value="<?php echo $name; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Description</label>
									<textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="3"><?php echo $description;?></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Address</label>
									<input type="text" name="address" class="form-control" id="address" placeholder="Enter Address" value="<?php echo $address; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Email</label>								
									<input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="<?php echo $email; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Phone Number</label>								
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone" value="<?php echo $phone; ?>">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Facebook</label>								
									<input type="text" name="facebook" class="form-control" id="facebook" placeholder="Enter facebook" value="<?php echo $facebook; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Google</label>								
									<input type="text" name="google" class="form-control" id="google" placeholder="Enter Google" value="<?php echo $google; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Twitter</label>								
									<input type="text" name="twitter" class="form-control" id="twitter" placeholder="Enter Twitter" value="<?php echo $twitter; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Instagram</label>								
									<input type="text" name="instagram" class="form-control" id="instagram" placeholder="Enter Instagram" value="<?php echo $instagram; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Logo</label>
									<div>
										<a href="<?php echo $logo[1];?>" target="_blank">
											<img src="<?php echo $logo[1];?>" class="logo_source" width="100">
										</a>
									</div>
									<input type="file" class="logo_file">
									<span class="logo_msg messagenotify"></span>
									<input type="hidden" id="image" name="image" class="logo_input" value="<?php echo $logo[0];?>">
								</div>
							</div>						
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="1">
								<input type="submit" class="btn btn-primary" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Stripe Payment</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form2" action="<?php echo getAdminUrl(); ?>/settings" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Stripe Public Key</label>								
									<input type="text" name="stripepublickey" class="form-control" id="stripepublickey" placeholder="Enter Stripe Public Key " value="<?php echo $stripepublickey; ?>">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Stripe Private Key </label>								
									<input type="text" name="stripeprivatekey" class="form-control" id="stripeprivatekey" placeholder="Enter Stripe Private Key" value="<?php echo $stripeprivatekey; ?>">
								</div>
							</div>			
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="1">
								<input type="submit" class="btn btn-primary" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Fee</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form3" action="<?php echo getAdminUrl(); ?>/settings" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Transaction Fee (Percentage)</label>
									<input type="number" class="form-control" name="transactionfee" placeholder="Transaction Fee" value="<?php echo $transactionfee; ?>" min="0" max="100">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Producer Event Fee</label>								
									<input type="text" class="form-control" name="producereventfee" placeholder="Enter Producer Event Fee" value="<?php echo $producereventfee; ?>">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Facility Stall Fee</label>								
									<input type="text" class="form-control" name="facilitystallfee" placeholder="Enter Facility Stall Fee" value="<?php echo $facilitystallfee; ?>">
								</div>
							</div>			
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="1">
								<input type="submit" class="btn btn-primary" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Google Analytics</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form4" action="<?php echo getAdminUrl(); ?>/settings" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Google analytics</label>
									<textarea class="form-control" id="googleanalytics" name="googleanalytics" placeholder="Enter Google Analytics" rows="3"><?php echo $googleanalytics?></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="1">
								<input type="submit" class="btn btn-primary" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">SMS Template</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form4" action="<?php echo getAdminUrl(); ?>/settings" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Sid</label>
									<input type="text" class="form-control" name="sid" placeholder="Sid" value="<?php echo $sid; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Token</label>								
									<input type="text" class="form-control" name="token" placeholder="Enter Token" value="<?php echo $token; ?>">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>From</label>								
									<input type="text" class="form-control" name="fromnumber" placeholder="Enter Form Mobile Number" value="<?php echo $fromnumber; ?>">
								</div>
							</div>			
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="1">
								<input type="submit" class="btn btn-primary" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
	<script>		
		$(function(){
			fileupload([".logo_file"], ['.logo_input', '.logo_source','.logo_msg']);
			
			validation(
                '#form1',
                {
                    name        : {
                        required  : true
                    },
					description   : {
                        required  : true
                    },
                    email       : {
                        required  : true,
                        email     : true   
                    },
					phone    	: {
                        required  : true,
                        phoneUS	  : true
                    },
					facebook    : {
                        required  : true
                    },                  
					google    : {
                        required  : true
                    },
					twitter    : {
                        required  : true
                    },				
					instagram    : {
                        required  : true
                    }
                },
                { 
                    email       : {
                        required  : "This field is required.",
                        email     : "Enter valid email address."
                    }
                }
			);
			validation(
				'#form2',
                {
                    stripepublickey       : {
                        required  : true
                    },
					stripeprivatekey      : {
                        required  : true
                    }
                    
                }
			);
			validation(
				'#form3',
                {
                    transactionfee        : {
                        required  : true
                    },
					producereventfee       : {
                        required  : true
                    },
					facilitystallfee        : {
                        required  : true
                    }
                    
                },

            );
            validation(
				'#form4',
                {
					googleanalytics        : {
                        required  : true
                    }
                    
                },

            );
			
		});
		$(function(){
			$('#phone').inputmask("(999) 999-9999");
		});

	jQuery.validator.addMethod("phoneUS", function(phone, element) {
	    mobile = mobile.replace(/\s+/g, "");
	    return this.optional(element) || mobile.length > 9 && 
	    mobile.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");

	</script>
<?php $this->endSection(); ?>
