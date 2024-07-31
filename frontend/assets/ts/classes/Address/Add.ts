import "jquery.growl";
import "bootstrap-inputmsg";
import { webuilder } from "webuilder";
export default class Add{
	private static $form = $('.redirect-address-add');
	private static runSourceTypeListener(){
		$('.changeSourceType', Add.$form).on('click', function(e){
			e.preventDefault();
			const source:JQuery = $('input[name=source]', Add.$form);
			const html:string = $(this).html() + ' <span class="caret"></span>';
			$('.sourceType', Add.$form).html(html);
			if($(this).data('type') == 'regex'){
				source.attr('pattern', '^/.+/i$');
				source.attr('placeholder', '/some pattern/i');
				$('input[name=regexr]', Add.$form).val(true);
			}else{
				source.attr('pattern', '(.)+');
				source.attr('placeholder', '');
				$('input[name=regexr]', Add.$form).val(false);
			}
			
		});
	}
	private static runFormSubmitListener(){
		Add.$form.on('submit', function(e){
			e.preventDefault();
			$(this).formAjax({
				success: (data: webuilder.AjaxResponse) => {
					$.growl.notice({
						title:"موفق",
						message:"انجام شد ."
					});
					window.location.href = data.redirect
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
		Add.runSourceTypeListener();
		Add.runFormSubmitListener();
	}
	public static initIfNeeded(){
		if(Add.$form.length){
			Add.init();
		}
	}
}