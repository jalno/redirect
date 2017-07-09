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
			$('input[name=regexr]', Edit.$form).val($(this).data('type') == 'regex' ? 1 : 0).trigger('change');
		});
		$('input[name=regexr]', Edit.$form).on('change', function(){
			const isRegex:boolean = parseInt($(this).val()) > 0;
			const html:string = $('.changeSourceType[data-type='+(isRegex ? 'regex' : 'link')+']').html() + '<span class="caret"></span>';
			$('.sourceType', Edit.$form).html(html);
			const source:JQuery = $('input[name=source]', Edit.$form);
			if(isRegex){
				source.attr('pattern', '^/.+/i?$');
				source.attr('placeholder', '/some pattern/(i)');
			}else{
				source.attr('pattern', '^https?://.+$');
				source.attr('placeholder', 'http(s)://source-address.com');
			}
		}).trigger('change');
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