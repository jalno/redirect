<?php
namespace themes\clipone\Views\Redirect\Address;
use \packages\base\Translator;
use \packages\userpanel;
use \packages\redirect\Address;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\Navigation\MenuItem;
use \packages\redirect\Views\Address\Edit as RedirectEdit;
class Edit extends RedirectEdit{
	use ViewTrait, FormTrait;
	protected $address;
	function __beforeLoad(){
		$this->address = $this->getAddress();
		$this->setTitle([
			Translator::trans('redirect.settings'),
			Translator::trans('redirects'),
			Translator::trans('redirect.address.edit')
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
					'data' => [
						'type' => $regexr ? 'regex' : 'link'
					],
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
