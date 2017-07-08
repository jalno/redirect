<?php
namespace themes\clipone\views\redirect\address;
use \packages\base\translator;
use \packages\userpanel;
use \packages\redirect\address;
use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;
use \themes\clipone\navigation\menuItem;
use \packages\redirect\views\address\add as redirectAdd;
class add extends redirectAdd{
	use viewTrait, formTrait;
	function __beforeLoad(){
		$this->setTitle([
			translator::trans('redirect.settings'),
			translator::trans('redirects'),
			translator::trans('redirect.address.add')
		]);
		navigation::active("settings/redirects");
	}
	protected function getStatusForSelect():array{
		return [
			[
				'title' => translator::trans("redirect.address.status.active"),
				'value' => address::active
			],
			[
				'title' => translator::trans("redirect.address.status.deactive"),
				'value' => address::deactive
			]
		];
	}
	protected function getTypeForSelect():array{
		return [
			[
				'title' => translator::trans("redirect.address.status.permanent"),
				'value' => address::permanent
			],
			[
				'title' => translator::trans("redirect.address.status.temporary"),
				'value' => address::temporary
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
					'text' => translator::trans($text),
					'dropdown' => [
						[
							'icon' => 'fa fa-link',
							'link' => '#',
							'class' => ['changeSourceType'],
							'data' => [
								'field' => 'source',
								'type' => 'link'
							],
							'title' => translator::trans('redirect.address.source.link')
						],
						[
							'icon' => 'fa fa-text-width',
							'link' => '#',
							'class' => ['changeSourceType'],
							'data' => [
								'field' => 'source',
								'type' => 'regex'
							],
							'title' => translator::trans('redirect.address.source.regex')
						]
					]
				]
			]
		];
	}
}
