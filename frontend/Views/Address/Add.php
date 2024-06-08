<?php
namespace themes\clipone\Views\Redirect\Address;
use \packages\base\Translator;
use \packages\userpanel;
use \packages\redirect\Address;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\Navigation\MenuItem;
use \packages\redirect\Views\Address\Add as RedirectAdd;
class Add extends RedirectAdd{
	use ViewTrait, FormTrait;
	function __beforeLoad(){
		$this->setTitle([
			Translator::trans('redirect.settings'),
			Translator::trans('redirects'),
			Translator::trans('redirect.address.add')
		]);
		Navigation::active("settings/redirects");
	}
	protected function getStatusForSelect():array{
		return [
			[
				'title' => Translator::trans("redirect.address.status.active"),
				'value' => Address::active
			],
			[
				'title' => Translator::trans("redirect.address.status.deactive"),
				'value' => Address::deactive
			]
		];
	}
	protected function getTypeForSelect():array{
		return [
			[
				'title' => Translator::trans("redirect.address.status.permanent"),
				'value' => Address::permanent
			],
			[
				'title' => Translator::trans("redirect.address.status.temporary"),
				'value' => Address::temporary
			]
		];
	}
	protected function getSourceInputGroup():array{
		$regexr = $this->getDataForm('regexr');
		$text = $regexr ? 'redirect.address.source.regex' : 'redirect.address.source.link';
		return [
			'left' => [
				[
					'type' => 'button',
					'class' => ['btn', 'btn-default', 'sourceType'],
					'icon' => $regexr ? 'fa fa-text-width' : 'fa fa-link',
					'text' => Translator::trans($text),
					'dropdown' => [
						[
							'icon' => 'fa fa-link',
							'link' => '#',
							'class' => ['changeSourceType'],
							'data' => [
								'field' => 'source',
								'type' => 'link'
							],
							'title' => Translator::trans('redirect.address.source.link')
						],
						[
							'icon' => 'fa fa-text-width',
							'link' => '#',
							'class' => ['changeSourceType'],
							'data' => [
								'field' => 'source',
								'type' => 'regex'
							],
							'title' => Translator::trans('redirect.address.source.regex')
						]
					]
				]
			]
		];
	}
}
