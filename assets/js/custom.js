function baseurl(){
	var base = window.location;

	if(base.host=='localhost'){
		return base.protocol + "//" + base.host + "/ezstall/";
	}else{
		return base.protocol + "//" + base.host + "/ezstall3/";
	}
}

/** Jquery Validation **/

function validation(selector, rules, messages, extras=[])
{
	var validation = {};

	validation['rules'] 			= 	rules;
	validation['messages'] 			=	messages;
	validation['errorElement'] 		= 	(extras['errorElement']) ? extras['errorElement'] : 'p';
	validation['errorClass'] 		= 	(extras['errorClass']) ? extras['errorClass'] : 'error_class_1';
	validation['ignore'] 			= 	(extras['ignore']) ? extras['ignore'] : ':hidden';
	validation['errorPlacement']	= 	function(error, element) {
											if(element.attr('data-error') == 'firstparent'){
												jQuery(element).parent().append(error);
											}else if(element.attr('data-error') == 'secondparent'){
												jQuery(element).parent().parent().append(error);
											}else{
												error.insertAfter(element);
											}
										}

	var validator = $(selector).validate(validation);						

	if(extras['callback']){
		return validator;
	}
}

/** Datatable **/

function ajaxdatatables(selector, options={}){
	if(options.destroy && options.destroy==1){
		$(selector).DataTable().destroy();
	}

	var datatableoptions = {
		'responsive'	: 	true, 
		'autoWidth' 	: 	false,
		'processing'	: 	true,
		'serverSide'	: 	true,	
		'ajax'			: 	{
								'url' 		: 	options.url,
								'data'		: 	(options.data) ? options.data : {},
								'dataType'	: 	'json',								
								'type'		: 	(options.method) ? options.method : 'post',
								'complete'	: 	function(){
													tooltip();
												}
							},	
		'columns'		: 	options.columns,
		'order'			: 	(options.order) ? options.order : [[0, 'desc']],
		'columnDefs'	: 	(options.columndefs) ? options.columndefs : [],
		'searching'		: 	(options.search && options.search=='0') ? false : true,
		'lengthMenu'	: 	(options.lengthmenu && options.lengthmenu.length > 0) ? options.lengthmenu : [10, 25, 50, 100],
	};

	var datatable = $(selector).DataTable(datatableoptions);	

	$(document).on('resize', function(){
		datatable.columns.adjust().responsive.recalc();
	})
}

function tooltip(){
	$('[data-toggle="tooltip"]').tooltip(); 
}


/** Select 2 **/

function select2(selector, options={}){
	$(selector).select2(options);
}


/** Sweet Alert 2 **/
function sweetalert2(action, data, extras=[]){

	Swal.fire({
		title: extras['title'] ? extras['title'] : 'Are you sure?',
		text: extras['text'] ? extras['text'] : "You want to proceed?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: extras['confirm'] ? extras['confirm'] : 'Yes',
		cancelButtonText: extras['cancel'] ? extras['cancel'] : 'No',
	}).then((result) => {
		if (result.value) {
			extras['method'] ? extras['method'] : formsubmit(action, data)
		}
	})
}


function formsubmit(action, data){
	$('<form action="'+action+'" method="post">'+data+'</form>').appendTo('body').submit();
}


/** Ajax **/

function ajax(url, data, extras=[]){  
    var options = {};
    
    options['url']          =   url;
    options['type']         =   (extras['type']) ? extras['type'] : 'post';
    options['data']         =   data;
    options['dataType']     =   (extras['datatype']) ? extras['datatype'] : 'json';
    
    if(extras['contenttype'])   options['contentType']  =   false;
    if(extras['processdata'])   options['processData']  =   false;
    if(extras['asynchronous'])  options['async']        =   false;
    if(extras['beforesend'])    options['beforeSend']   =   extras['beforesend'];
    if(extras['complete'])      options['complete']     =   extras['complete'];
    
    if(extras['success']){
        options['success']      =   extras['success'];
    }else if(extras['method']){
        options['success']      =   function(data){ 
                                        extras['method'](data);
                                    }
    }   
    
    if(extras['error']){
        options['error']      =   extras['error'];
    }
	
    $.ajax(options);
}


function filetype(data1, data2){

	var type 		= data1[0];
	var multiple 	= (data1[2]) ? data1[2] : '';
	var selector 	= data2[0];
	var name 		= data2[1];
	var input		= data2[2][0];
	var source		= data2[2][1];

	if(type=='image'){

		var data = 	'\
						<div class="imagecontent">\
							<a href="'+source+'" target="_blank">\
								<img src="'+source+'" class="'+name+'_source" width="100">\
							</a>\
						</div>\
					';

	}else if(type=='video'){

		var data = 	'\
						<div class="videocontent">\
							<video width="320" height="240" controls>\
								<source src="'+source+'" type="video/mp4" class="'+name+'_source">\
							</video>\
						</div>\
					';

	}else if(type=='audio'){

		var data = 	'\
						<div class="audiocontent">\
							<audio controls>\
								<source src="'+source+'" type="audio/mpeg" class="'+name+'_source">\
							</audio>\
						</div>\
					';
	}

	if(multiple==''){
		var fields = '<input type="file" class="'+name+'_file"><input type="hidden" name="'+name+'" class="'+name+'_input" value="'+input+'">';
	}else{
		var fields = '<div class="'+name+'multiple"></div><div class="clear"></div><input type="file" class="'+name+'_file" multiple>';
	}

	$(selector).html(data+fields);

	if(multiple!=''){

		var multipledata 	= (data2[3] && data2[3][0] && data2[3][0]!='') ? data2[3][0].split(',') : '';
		var multipleurl 	= (data2[3] && data2[3][1] && data2[3][1]!='') ? data2[3][1] : '';

		if(multipledata!=''){
			$(multipledata).each(function(i, v){
				multiplefileappend(name, v, multipleurl+'/'+v)
			})
		}

		$(document).on('click', '.multipleupload i', function(){
			if($(this).parent().parent().find('.multipleupload').length=='1') $(this).parent().parent().parent().find('.imagecontent').show();
			$(this).parent().remove();
		})		
	}
}


function multiplefileappend(name, value, src){

	var data = 	'<div class="multipleupload">\
					<input type="hidden" value="'+value+'" name="'+name+'[]">\
					<img src="'+src+'" width="100">\
					<i class="fas fa-times"></i>\
				</div>';

	$(document).find('.'+name+'multiple').append(data);
	$(document).find('.'+name+'multiple').parent().find('.imagecontent').hide();
}


function fileupload(data1=[], data2=[], data3=[]){ 
	var url 			= baseurl()+'ajax/fileupload';
	var path			= baseurl()+'assets/uploads/temp/';
	var relativepath	= './assets/uploads/temp/';
	var pdfimg			= baseurl()+'assets/images/pdf.png';
	
	var selector 		= data1[0];
	var extension 		= data1[1] ? data1[1] : ['jpg','jpeg','png','gif','tiff','tif'];
	
	$(document).on('change', selector, function(){
		var name 		= $(this).val();
		var ext 		= name.split('.').pop().toLowerCase();
		
		if($.inArray(ext, extension) !== -1){
            var formdata    = new FormData();
            formdata.append("file", $(selector)[0].files[0]);
            formdata.append("path", relativepath);
            formdata.append("type", extension.join('|'));
            formdata.append("name", name);
            formdata.append("resize", data3);
			
			ajax(url, formdata, { contenttype : 1, processdata : 1, method : fileappend});
		}else{
			$(selector).val('');
			alert('Supported file format are '+extension.join(','));
		}
	})
	
	function fileappend(data){
        if(data.success && data2.length){           
            var file        = data.success;
            var fileinput   = (data2[0]) ? data2[0] : '';
            var filesource  = (data2[1]) ? data2[1] : '';
            
            var ext = file.split('.').pop().toLowerCase();
            
            if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='tif' || ext=='tiff'){
                var filesrc = path+'/'+file;
            }else if(ext=='pdf'){
                var filesrc = pdfimg;
            }
            
            if(fileinput!=''){
                $(fileinput).val(file);
            }
            
            if(filesource!='' && filesrc){
                $(filesource).attr('src', filesrc);
                $(filesource).parent().attr('href', filesrc);
            }
        }
        
        $(selector).val('');
    }
}

/** Tinymce Editor **/

function editor(selector, height=300){

	tinymce.init({
		selector	: 	selector,
		height		: 	height,
		statusbar	: 	false,
		menubar		: 	false,
	  	plugins		: 	'code', 
	 	toolbar		: 	'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl | code',
		
	});
}


/** Datepicker **/

function dateformat(selector, extras=[]){

	var options = {};	
	options['format'] 		= 'mm-dd-yyyy';
	options['autoclose'] 	= true;

	if($.inArray('startdate', extras) != -1) options['startDate'] = new Date();
	
	$(selector).datepicker(options).on('keypress paste', function(e){
		e.preventDefault();
		return false;
	});
}

function uidatepicker(selector, extras=[]){

	var options = {};	
	options['dateFormat'] 		= 'mm-dd-yy';
	options['changeMonth'] 		= true;
	options['changeYear'] 		= true;
	
	if(extras['mindate'])	options['minDate'] = extras['mindate'];
	if(extras['maxdate'])	options['maxDate'] = extras['maxdate'];
	if(extras['close'])		options['onClose'] = extras['close'];
	
	$(selector).datepicker(options);
}

$(".navbar-toggler.collapsed").click(function() {
    $(".navbar-collapse.collapse").slideToggle();
});

$('#sidebarCollapse').click(function() {
    $('.navbar-header').toggleClass("show-sidebar");
});

function barnstall(barnstallname, barnstallitem=[], barnstallresult=[]){  
	var selector_btn1 		= barnstallitem[0][0];
	var barn_append 		= barnstallitem[1][0];
	var stall_append 		= barnstallitem[1][1];
	var barnIndex 			= barnstallitem[2][0];
	var stallIndex 			= barnstallitem[2][1];
	var barn_validation 	= barnstallitem[3][0];  
	var usertype			= barnstallitem[4][0]; 
	var charging_flagdata	= barnstallitem[4][1]; 
	var nobtn				= barnstallitem[4][2] ? barnstallitem[4][2]  : ''; 
	
	var price_flagdata		= []; 
	var price_feedata		= [];  	
	if($(document).find("#price_flag").length && $(document).find("#price_fee").length){
		price_flagdata 	= $(document).find("#price_flag").val().split(',');
		price_feedata 	= $(document).find("#price_fee").val().split(',');
			
		$(document).on("change", "#price_flag, #price_fee", function() {
			price_flagdata 	= $(document).find("#price_flag").val().split(',');
			price_feedata 	= $(document).find("#price_fee").val().split(',');
		});
	}
	
	/*PLACEHOLDER*/
	if(barnstallname=='barn'){
		var srheading  		= 'Stalls';
		var srrate     		= 'Stalls Rate';
		var srname     		= 'Stalls Name';
		var srprice    		= 'Stalls Price';
		var srnightprice    = 'Stalls Night Price';
		var srweekprice    	= 'Stalls Week Price';
		var srmonthprice    = 'Stalls Month Price';
		var srflatprice    	= 'Stalls Flat Price';
		var srimage    		= 'Stalls Image';
		var srtotalnumber 	= 'Total Number of Stalls';
		var srfirstnumber 	= 'First Stalls Number';
		var uploadName  	= 'Barn';
		var stallcamp 		= 'Stall';
		var BarnLots    	= 'Barn';
	}else if(barnstallname=='rvhookups'){
		var srheading  		= 'RV Lots';
		var srrate     		= 'RV Lots Rate';
		var srname     		= 'RV Lots Name';
		var srprice    		= 'RV Lots Price';
		var srnightprice    = 'RV Lots Night Price';
		var srweekprice    	= 'RV Lots Week Price';
		var srmonthprice    = 'RV Lots Month Price';
		var srflatprice    	= 'RV Lots Flat Price';
		var srimage    		= 'RV Lots Image';
		var srtotalnumber 	= 'Total Number of RV Lots';
		var srfirstnumber 	= 'First RV Lots Number';
		var uploadName  	= 'Campsites';
		var stallcamp 		= 'Rv Lots';
		var BarnLots    	= 'Campsites';
	}
	/*PLACEHOLDER*/
	
	var bsresult = barnstallresult[0] ? barnstallresult[0] : [];
	var occupied = barnstallresult[1] ? barnstallresult[1] : [];
	var reserved = barnstallresult[2] ? barnstallresult[2] : [];
	
	/* START ADD EDIT BARN */
	var barndata = function(result=[], type=''){ 
		var activeclass = $.trim($(barn_append).html())=='' ? 'active' : '';
		
		var barnId   	= result['id'] ? result['id'] : '';
		var barnName 	= result['name'] ? result['name'] : '';
		var stall		= result['stall'] ? result['stall'] : (result['rvstall'] ? result['rvstall'] : []);		

		var barntab='\
			<li class="nav-item text-center mb-3">\
				<a class="nav-link tab-link " data-bs-toggle="pill" data-bs-target="#tabtarget_'+barnstallname+'_'+barnIndex+'">\
					<input type="text" id="barn_'+barnstallname+'_'+barnIndex+'_name" name="'+barnstallname+'['+barnIndex+'][name]" class="form-control " placeholder="Enter '+uploadName+' Name" value="'+barnName+'"">\
				</a>\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][id]" value="'+barnId+'">\
			</li>\
		';
		
		if(nobtn==''){
			var stalltabbtns	= '\
				<button class="btn-stall stallbtn_'+barnstallname+'" data-barnIndex="'+barnIndex+'" >Add '+stallcamp+'</button>\
				<button class="btn-stall bulkstallmodal_'+barnstallname+'" data-barnIndex="'+barnIndex+'" data-bs-toggle="modal" data-bs-target="#bulkstallmodal_'+barnstallname+'">Add Bulk '+stallcamp+'</button>\
				<a href="javascript:void(0);" class="btn btn-info bulkbtn_'+barnstallname+'">Upload '+uploadName+'</a>\
				<input type="file" class="bulkfile_'+barnstallname+'" style="display:none;">\
				<button class="btn-stall barnremovebtn_'+barnstallname+'">Remove '+BarnLots+' and '+stallcamp+'</button>\
			';
		}
		
		var stalltab = '\
			<div id="tabtarget_'+barnstallname+'_'+barnIndex+'" class="stallcontainer container tab-pane p-0 mb-3 '+activeclass+'">\
				<div class="col-md-11 p-0 my-3 stallbtns">\
					<input type="hidden" name="stallvalidation_'+barnstallname+'_'+barnIndex+'" id="stallvalidation_'+barnstallname+'_'+barnIndex+'">\
					'+(stalltabbtns ? stalltabbtns : '')+'\
				</div>\
			</div>\
		';

		$(barn_append).append(barntab);
		$(stall_append).append(stalltab);
		$(barn_validation).val('1');
		$(barn_validation).valid();

		$(document).find('#barn_'+barnstallname+'_'+barnIndex+'_name').rules("add", {required: true});
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).rules("add", {required: true});

		if(stall.length > 0){
			$(stall).each(function(i, v){
				stalldata(barnIndex, v)
			});
		}
		
		if(type=='1') stalldata(barnIndex);
		++barnIndex;
	}
	/* END ADD EDIT BARN */
	
	
	/* START ADD EDIT STALL */	
	var stalldata = function(barnIndex, result=[])
	{ 
		var stallId       		= result['id'] ? result['id'] : '';
		var charging_flags      = result['charging_id'] ? result['charging_id'] : ''; 
		var stallName     		= result['name'] ? result['name'] : '';
		var stallPrice    		= result['price'] ? result['price'] : '';
		var stallNightPrice    	= result['night_price'] ? result['night_price'] : (price_feedata[0] ? price_feedata[0] : '');
		var stallWeekPrice    	= result['week_price'] ? result['week_price'] : (price_feedata[1] ? price_feedata[1] : '');
		var stallMonthPrice    	= result['month_price'] ? result['month_price'] : (price_feedata[2] ? price_feedata[2] : '');
		var stallFlatPrice    	= result['flat_price'] ? result['flat_price'] : (price_feedata[3] ? price_feedata[3] : '');
		var stallImage    		= result['image'] ? result['image'] : '';
		var stallBulkImage    	= result['bulkimage'] ? result['bulkimage'] : '';
		var block_unblock      	= result['block_unblock'] ? result['block_unblock'] : ''; 
		var checked				= (block_unblock=='1') ? 'checked' : '';
		
		if(stallImage!='' && stallBulkImage==''){
			var stallImages   	= baseurl()+'assets/uploads/stall/'+stallImage;
		}else if(stallBulkImage!=''){
			var stallImages   	= baseurl()+'assets/uploads/temp/'+stallBulkImage;
		}else{
			var stallImages   	= baseurl()+'assets/images/upload.png';
		}
		
		var charging_flag= '';
		$.each(charging_flagdata, function(i,v){
			var selected = i==charging_flags ? 'selected' : '';
			charging_flag += '<option value='+i+' '+selected+'>'+v+'</option>';
		})
	
		var availability = '';
		var blockunblock = '';
		
		if(nobtn==''){
			availability = 	'<a href="javascript:void(0);" class="dash-stall-remove fs-7 stallremovebtn_'+barnstallname+'" data-barnIndex="'+barnIndex+'"><i class="fas fa-times text-white"></i></a>';
		}else{
			blockunblock = 	'<div class="col-md-6 mb-3">\
								<input type="checkbox" id="stall_'+barnstallname+'_'+stallIndex+'_block_unblock" '+checked+' name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][block_unblock]" value="1">  Reserved\
							</div>';
		}
		
		if($.inArray(stallId, occupied) !== -1)	availability = '<span class="red-box"></span>';
		if($.inArray(stallId, reserved) !== -1)	availability = '<span class="yellow-box"></span>';
	
		var stallbox = '';
		if(usertype==2){
			stallbox = 	'<div class="col-md-6 mb-3">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_name" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][name]" class="form-control  fs-7" placeholder="Enter Your '+srname+'" value="'+stallName+'">\
						</div>\
						<div class="col-md-6 mb-3 pricelistwrapper1 '+(price_flagdata[0] && price_flagdata[0]==1 ? '' : 'displaynone')+'">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_night_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][night_price]" class="form-control fs-7" placeholder="Enter Your '+srnightprice+'" value="'+stallNightPrice+'">\
						</div>\
						<div class="col-md-6 mb-3 pricelistwrapper2 '+(price_flagdata[1] && price_flagdata[1]==1 ? '' : 'displaynone')+'">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_week_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][week_price]" class="form-control fs-7" placeholder="Enter Your '+srweekprice+'" value="'+stallWeekPrice+'">\
						</div>\
						<div class="col-md-6 mb-3 pricelistwrapper3 '+(price_flagdata[2] && price_flagdata[2]==1 ? '' : 'displaynone')+'">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_month_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][month_price]" class="form-control fs-7" placeholder="Enter Your '+srmonthprice+'" value="'+stallMonthPrice+'">\
						</div>\
						<div class="col-md-6 mb-3 pricelistwrapper4 '+(price_flagdata[3] && price_flagdata[3]==1 ? '' : 'displaynone')+'">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_flat_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][flat_price]" class="form-control fs-7" placeholder="Enter Your '+srflatprice+'" value="'+stallFlatPrice+'">\
						</div>';
		}else if(usertype==3){
			stallbox = 	'<div class="col-md-6 mb-3">\
							<select class="form-control" id="stall_'+barnstallname+'_'+stallIndex+'_chargingflag" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][chargingflag]">\
							'+charging_flag+'\
							</select>\
						</div>\
						<div class="col-md-6 mb-3">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_name" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][name]" class="form-control  fs-7" placeholder="Enter Your '+srname+'" value="'+stallName+'">\
						</div>\
						<div class="col-md-6 mb-3">\
							<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][price]" class="form-control fs-7" placeholder="Enter Your '+srprice+'" value="'+stallPrice+'">\
						</div>';
		}
		
		var data='\
		<div class="row mb-2 dash-stall-base">\
			'+stallbox+'\
			<div class="col-md-3 mb-3">\
				<a href="'+stallImages+'" target="_blank">\
					<img src="'+stallImages+'" class="stall_image_source_'+barnstallname+'_'+stallIndex+'" width="40" height="35">\
				</a>\
				<button class="dash-upload stalluploadimage_'+barnstallname+' fs-7" title="Upload image here">Upload</button>\
				<input type="file" class="stallimage stall_image_file_'+barnstallname+'_'+stallIndex+'" style="display:none;">\
				<span class="stall_image_msg'+stallIndex+'"></span>\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][image]" class="stall_image_input_'+barnstallname+'_'+stallIndex+'" value="'+stallImage+'">\
			</div>\
			'+blockunblock+'\
			<div class="col-md-1 mb-3 delete">\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][id]" value="'+stallId+'" class="stall_id">\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][status]" value="1">\
				'+availability+'\
			</div>\
		</div>\
		';
		
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).val('1');
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).valid();
		
		$(document).find('#tabtarget_'+barnstallname+'_'+barnIndex).find('.stallbtns').before(data); 

		fileupload(['.stall_image_file_'+barnstallname+'_'+stallIndex], ['.stall_image_input_'+barnstallname+'_'+stallIndex, '.stall_image_source_'+barnstallname+'_'+stallIndex, '.stall_image_msg_'+barnstallname+'_'+stallIndex]);
		
		$(document).find('#stall_'+barnstallname+'_'+stallIndex+'_name').rules("add", {required: true});
		$(document).find('#stall_'+barnstallname+'_'+stallIndex+'_price').rules("add", {required: true});
		++stallIndex;
	}
	/* END ADD EDIT STALL */
	
	/* START BARN AND STALL CLICK AND RESULT */	
	$(selector_btn1).off('click');
	$(selector_btn1).click(function(e){
		e.preventDefault();
		barndata([], 1);
	});

	if(bsresult.length > 0){
		$(bsresult).each(function(i, v){
			barndata(v);
		});
	}
	
	$(document).off('click', '.stallbtn_'+barnstallname);
	$(document).on('click', '.stallbtn_'+barnstallname, function(e){ 
		e.preventDefault();
		stalldata($(this).attr('data-barnIndex'));
	});
	/* END BARN AND STALL CLICK AND RESULT */
	
	/* START BARN REMOVE */
	$(document).off('click', '.barnremovebtn_'+barnstallname);
	$(document).on('click', '.barnremovebtn_'+barnstallname, function(e){
		e.preventDefault();
		var stalltabparent = $(this).closest('.stallcontainer');
		$(document).find('[data-bs-target="#'+stalltabparent.attr('id')+'"]').parent().remove();
		stalltabparent.remove();
		
		if($(document).find(barn_append+' li').length){
			$(document).find(barn_append+' li:first a').addClass('active');
			$(document).find(stall_append+' div:first').addClass('active');
		}else{
			$(barn_validation).val('');
			$(barn_validation).valid();
		}
	});
	/* END BARN REMOVE */

	
	/* START STALL REMOVE */
	$(document).off('click', '.stallremovebtn_'+barnstallname);
	$(document).on('click', '.stallremovebtn_'+barnstallname, function(e){
		e.preventDefault();
		var bi = $(this).attr('data-barnIndex')
		var stallparent = $(this).closest('.stallcontainer');
		$(this).closest('.dash-stall-base').remove();
		
		if(stallparent.find('.dash-stall-base').length==0){
			$(document).find('#stallvalidation_'+barnstallname+'_'+bi).val('');
			$(document).find('#stallvalidation_'+barnstallname+'_'+bi).valid();
		}
	})
	/* END STALL REMOVE */
	
	
	/* START STALL IMAGE CLICK */
	$(document).off('click','.stalluploadimage_'+barnstallname)
	$(document).on('click','.stalluploadimage_'+barnstallname, function (e) {
		e.preventDefault();
		$(this).parent().find('.stallimage').click();
	});
	/* END STALL IMAGE CLICK */
	
	
	/* START BULK UPLOAD */
	$(document).off('click','.bulkbtn_'+barnstallname)
	$(document).on('click','.bulkbtn_'+barnstallname, function () {
		$(this).parent().find('.bulkfile_'+barnstallname).click();
	});

	$(document).on('change', '.bulkfile_'+barnstallname, function () {
		var _this = $(this);
  		var formdata = new FormData();
		formdata.append('file', $(this)[0].files[0]); 
		
		ajax(
			baseurl()+'ajax/importbarnstall', 
			formdata, 
			{
				contenttype : 1,
				processdata : 1,
				success: function(result) {
					$(result).each(function(i, v){
						barndata(v)
					})
					
					_this.val('');
				}
			}
		);
	});
	/* END BULK UPLOAD */
	
	
	/* START STALL BULK UPLOAD */
	$('#bulkstallmodal_'+barnstallname).remove();
	
	var charging_flagmodal ='';
	$.each(charging_flagdata, function(i,v){ 
		charging_flagmodal += '<option value='+i+'>'+v+'</option>';
	})
	
	if(usertype==2){
		var modaldata 	= 	'<div class="col-md-12 my-2">\
								<div class="form-group">\
									<label>'+srname+'</label>\
									<input type="text" class="form-control stall_name_'+barnstallname+'" placeholder="Enter Your '+srname+'">\
								</div>\
							</div>\
							<div class="col-md-12 my-2 pricelistwrapper1 '+(price_flagdata[0] && price_flagdata[0]==1 ? '' : 'displaynone')+'">\
								<div class="form-group">\
									<label>'+srnightprice+'</label>\
									<input type="number" class="form-control stall_night_price_'+barnstallname+'" placeholder="Enter Your '+srnightprice+'" value="'+(price_feedata[0] ? price_feedata[0] : '')+'">\
								</div>\
							</div>\
							<div class="col-md-12 my-2 pricelistwrapper2 '+(price_flagdata[1] && price_flagdata[1]==1 ? '' : 'displaynone')+'">\
								<div class="form-group">\
									<label>'+srnightprice+'</label>\
									<input type="number" class="form-control stall_week_price_'+barnstallname+'" placeholder="Enter Your '+srweekprice+'" value="'+(price_feedata[1] ? price_feedata[1] : '')+'">\
								</div>\
							</div>\
							<div class="col-md-12 my-2 pricelistwrapper3 '+(price_flagdata[2] && price_flagdata[2]==1 ? '' : 'displaynone')+'">\
								<div class="form-group">\
									<label>'+srmonthprice+'</label>\
									<input type="number" class="form-control stall_month_price_'+barnstallname+'" placeholder="Enter Your '+srmonthprice+'" value="'+(price_feedata[2] ? price_feedata[2] : '')+'">\
								</div>\
							</div>\
							<div class="col-md-12 my-2 pricelistwrapper4 '+(price_flagdata[3] && price_flagdata[3]==1 ? '' : 'displaynone')+'">\
								<div class="form-group">\
									<label>'+srflatprice+'</label>\
									<input type="number" class="form-control stall_flat_price_'+barnstallname+'" placeholder="Enter Your '+srflatprice+'" value="'+(price_feedata[3] ? price_feedata[3] : '')+'">\
								</div>\
							</div>';
	}else if(usertype==3){
		var modaldata 	= 	'<div class="col-md-12 my-2">\
								<div class="form-group">\
									<label>'+srrate+'</label>\
									<select class="form-control stall_charging_id_'+barnstallname+'">\
									'+charging_flagmodal+'\
									</select>\
								</div>\
							</div>\
							<div class="col-md-12 my-2">\
								<div class="form-group">\
									<label>'+srname+'</label>\
									<input type="text" class="form-control stall_name_'+barnstallname+'" placeholder="Enter Your '+srname+'">\
								</div>\
							</div>\
							<div class="col-md-12 my-2">\
								<div class="form-group">\
									<label>'+srprice+'</label>\
									<input type="number" class="form-control stall_price_'+barnstallname+'" placeholder="Enter Your '+srprice+'">\
								</div>\
							</div>';
	}

	
	var modal = '<div class="modal fade" id="bulkstallmodal_'+barnstallname+'" role="dialog">\
					<div class="modal-dialog">\
						<div class="modal-content">\
							<div class="modal-header">\
								<h4 class="modal-title">'+srheading+'</h4>\
								<button type="button" class="close" data-bs-dismiss="modal">&times;</button>\
							</div>\
							<div class="modal-body">\
								'+modaldata+'\
								<div class="col-md-6 my-2">\
									<div class="form-group">\
										<label>'+srimage+'</label>\
										<div>\
											<a href="" target="_blank">\
												<img src="" class="stall_source_'+barnstallname+'" width="100">\
											</a>\
										</div>\
										<input type="file" class="stall_file_'+barnstallname+'">\
										<input type="hidden" class="stall_input_'+barnstallname+'" value="">\
									</div>\
								</div>	\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>'+srtotalnumber+'</label>\
										<input type="number" class="form-control stall_total_'+barnstallname+'" placeholder="Enter Your '+srtotalnumber+'" min="1" required>\
									</div>\
								</div>\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>'+srfirstnumber+'</label>\
										<input type="text" class="form-control stall_number_'+barnstallname+'" placeholder="Enter Your '+srfirstnumber+'" min="1" required>\
									</div>\
								</div>\
							</div>\
							<div class="modal-footer">\
								<input type="hidden" class="barnIndexValue_'+barnstallname+'" value="0">\
								<button type="button" class="btn btn-info bulkstallbtn_'+barnstallname+'">Submit</button>\
								<button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>\
							</div>\
						</div>\
					</div>\
				</div>';
	
	$('body').append(modal);	
	fileupload(['.stall_file_'+barnstallname], ['.stall_input_'+barnstallname, '.stall_source_'+barnstallname]);
	
	$('#bulkstallmodal_'+barnstallname).on('shown.bs.modal', function (e) { 
		$('.stall_name_'+barnstallname+', .stall_price_'+barnstallname+', .stall_input_'+barnstallname+', .stall_file_'+barnstallname+', .stall_total_'+barnstallname+', .stall_number_'+barnstallname).val('');
		$('.stall_source_'+barnstallname).attr('src', baseurl()+'assets/images/upload.png');
		$('.stall_source_'+barnstallname).parent().attr('href', baseurl()+'assets/images/upload.png');
	})

	$(document).off('click', '.bulkstallmodal_'+barnstallname)
	$(document).on('click', '.bulkstallmodal_'+barnstallname, function (e) { 
		e.preventDefault();
		$('.barnIndexValue_'+barnstallname).val($(this).attr('data-barnIndex'));
	});

	$(document).off('click', '.bulkstallbtn_'+barnstallname)
	$(document).on('click', '.bulkstallbtn_'+barnstallname, function(e){ 
		e.preventDefault();
		if($('.stall_total_'+barnstallname+'').val()==''){
			$('.stall_total_'+barnstallname+'').focus();
			return false;
		}

		var name          	= $('.stall_name_'+barnstallname).val();
		var nightprice      = $('.stall_night_price_'+barnstallname).val();
		var weekprice       = $('.stall_week_price_'+barnstallname).val();
		var monthprice      = $('.stall_month_price_'+barnstallname).val();
		var flatprice       = $('.stall_flat_price_'+barnstallname).val();
		var price        	= $('.stall_price_'+barnstallname).val();
		var charging_id    	= $('.stall_charging_id_'+barnstallname).val(); 
		var image        	= $('.stall_input_'+barnstallname).val();
		var stalltotal    	= $('.stall_total_'+barnstallname).val();
		var stallnumber 	= $('.stall_number_'+barnstallname).val(); 
		var barnIndexValue	= $('.barnIndexValue_'+barnstallname).val();

		for(var i=0; i<stalltotal; i++){ 
			var names = stallnumber!='' ? name+' '+stallnumber : name; 
			stalldata(barnIndexValue, {name:names,charging_id: charging_id,night_price:nightprice,week_price:weekprice,month_price:monthprice,flat_price:flatprice,price:price,status:1,bulkimage:image});
			if(stallnumber!='') stallnumber++ ;
		}

		$('#bulkstallmodal_'+barnstallname).modal('hide');
	});
	/* END STALL BULK UPLOAD */
}


function products(productsname, productsitem=[], productsresult=[]){ 
	var selector_btn1 	= productsitem[0][0];
	var product_append 	= productsitem[1][0];
	var productIndex 	= productsitem[2][0];
	var notbtn			= productsitem[2][1] ? productsitem[2][1] : '';

	var productsresult 	= productsresult[0] ? productsresult[0] : [];
	
	if(productsresult.length > 0){
		$(productsresult).each(function(i, v){
			productsdata(v);
		});
	}
	
	/* START ADD EDIT PRODUCTS */
	$(selector_btn1).off('click');
	$(selector_btn1).click(function(e){
		e.preventDefault();
		productsdata();
	});
	
	function productsdata(result=[])
	{  
		var productId       		= result['id'] ? result['id'] : '';
		var productName     		= result['name'] ? result['name'] : ''; 
		var productQuantity    		= result['quantity'] ? result['quantity'] : '';
		var productPrice    		= result['price'] ? result['price'] : ''; 
		
		var deletebtn = '';
		var blockunblock = '';
		
		if(notbtn==''){
			deletebtn = '<a href="javascript:void(0);" class="dash-stall-remove fs-7 productremovebtn_'+productsname+'"><i class="fas fa-times text-white"></i></a>';
		}else{
			blockunblock = 	'<div class="col-md-2 mb-4">\
								<input type="checkbox" id="product_'+productsname+'_'+productIndex+'_block_unblock" name="'+productsname+'['+productIndex+'][block_unblock]" value="1">  Reserved\
							</div>';
		}
		
		var data='\
		<div class="row mb-2 dash-stall-base">\
			<div class="col-md-6 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_name" name="'+productsname+'['+productIndex+'][name]" required class="form-control fs-7" placeholder="Name" value="'+productName+'">\
			</div>\
			<div class="col-md-2 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_quantity" name="'+productsname+'['+productIndex+'][quantity]" required class="form-control fs-7" placeholder="Quantity" value="'+productQuantity+'">\
			</div>\
			<div class="col-md-2 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_price" name="'+productsname+'['+productIndex+'][price]" required class="form-control fs-7" placeholder="Price" value="'+productPrice+'">\
			</div>\
			'+blockunblock+'\
			<div class="col-md-1 mb-4 delete">\
				'+deletebtn+'\
				<input type="hidden" name="'+productsname+'['+productIndex+'][id]" value="'+productId+'">\
				<input type="hidden" name="'+productsname+'['+productIndex+'][status]" value="1">\
			</div>\
		</div>\
		';
		
		$(document).find('#product_'+productsname+'_'+productIndex+'_name').rules("add", {required: true});
		$(document).find('#product_'+productsname+'_'+productIndex+'_quantity').rules("add", {required: true});
		$(document).find('#product_'+productsname+'_'+productIndex+'_price').rules("add", {required: true});
		++productIndex;
		
		$(product_append).append(data);
	}
	/* END ADD EDIT PRODUCTS */
	
	
	/* START PRODUCTS REMOVE */
	$(document).off('click', '.productremovebtn_'+productsname)
	$(document).on('click', '.productremovebtn_'+productsname, function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
	})
	/* END PRODUCTS REMOVE */
}