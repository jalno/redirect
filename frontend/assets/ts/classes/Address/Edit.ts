import * as $ from "jquery";
import "jquery.growl";
import "bootstrap-inputmsg";
import { webuilder } from "webuilder";
export default class Edit{
	private static $form = $('.redirect-address-edit');
	private static firstFlag:boolean;
	private static runSourceTypeListener(){
		$('.changeSourceType', Edit.$form).on('click', function(e){
			e.preventDefault();
			if(Edit.firstFlag || $('.sourceType', Edit.$form).data('type') == $(this).data('type')){
				Edit.firstFlag = true;
				const source:JQuery = $('input[name=source]', Edit.$form);
				const html:string = $(this).html() + ' <span class="caret"></span>';
				$('.sourceType', Edit.$form).html(html);
				if($(this).data('type') == 'regex'){
					source.attr('pattern', '^/.+/i$');
					source.attr('placeholder', '/some pattern/i');
					$('input[name=regexr]', Edit.$form).val(true);
				}else{
					source.attr('pattern', '^https?://.+$');
					source.attr('placeholder', 'http(s)://source-address.sth');
					source.attr('title', 'http(s)://source-address.sth');
					$('input[name=regexr]', Edit.$form).val(false);
				}
			}
			
		}).trigger('click');
	}
	private static runFormSubmitListener(){
		Edit.$form.on('submit', function(e){
			e.preventDefault();
			$(this).formAjax({
				success: (data: webuilder.AjaxResponse) => {
					$.growl.notice({
						title:"موفق",
						message:"تغییرات با موفقیت ذخیره شد ."
					});
				},
				error: function(error:webuilder.AjaxError){
					if(error.error == 'data_duplicate' || error.error == 'data_validation'){
						let $input = $('[name='+error.input+']');
						let $params = {
							title: 'خطا',
							message:''
						};
						if(error.error == 'data_validation'){
							$params.message = 'داده وارد شده معتبر نیست';
						}else if(error.error == 'data_duplicate'){
							$params.message = 'داده وارد شده تکراری است';
						}
						if($input.length){
							$input.inputMsg($params);
						}else{
							$.growl.error($params);
						}
					}else{
						$.growl.error({
							title:"خطا",
							message:'درخواست شما توسط سرور قبول نشد'
						});
					}
				}
			});
		});
	}
	public static init(){
		Edit.runSourceTypeListener();
		Edit.runFormSubmitListener();
	}
	public static initIfNeeded(){
		if(Edit.$form.length){
			Edit.init();
		}
	}
}