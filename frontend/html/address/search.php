<?php
use \packages\base\Translator;
use \packages\userpanel;
use \packages\userpanel\Sate;
use \themes\clipone\Utility;
use \packages\redirect\Address;
$this->the_header();
?>
<div class="row">
	<div class="col-xs-12">
	<?php if(!empty($this->getAddressLists())){ ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link"></i> <?php echo Translator::trans('redirects'); ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link tooltips" title="<?php echo Translator::trans('redirect.search'); ?>" href="#search" data-toggle="modal" data-original-title=""><i class="fa fa-search"></i></a>
					<?php if($this->canAdd){ ?>
					<a class="btn btn-xs btn-link tooltips" title="<?php echo Translator::trans('redirect.address.add'); ?>" href="<?php echo userpanel\url('settings/redirects/add'); ?>"><i class="fa fa-plus"></i></a>
					<?php } ?>
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<?php
						$hasButtons = $this->hasButtons();
						?>
						<thead>
							<tr>
								<th class="center">#</th>
								<th><?php echo Translator::trans('redirect.address.source'); ?></th>
								<th><?php echo Translator::trans('redirect.address.type'); ?></th>
								<th><?php echo Translator::trans('redirect.address.destination'); ?></th>
								<th><?php echo Translator::trans('redirect.address.hits'); ?></th>
								<th><?php echo Translator::trans('redirect.address.status'); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($this->getAddressLists() as $address){
								$this->setButtonParam('edit', 'link', userpanel\url("settings/redirects/edit/".$address->id));
								$this->setButtonParam('delete', 'link', userpanel\url("settings/redirects/delete/".$address->id));
								$statusClass = Utility::switchcase($address->status, [
									'label label-success' => Address::active,
									'label label-danger' => Address::deactive
								]);
								$statusTxt = Utility::switchcase($address->status, [
									'redirect.address.status.active' => Address::active,
									'redirect.address.status.deactive' => Address::deactive,
								]);
							?>
							<tr>
								<td class="center"><?php echo $address->id; ?></td>
								<td class="ltr">
								<?php
								if($address->isRegex()){
									echo $address->source;
								}else{ ?>
									<a href="<?php echo $address->source; ?>" target="_blank"><?php echo $address->source; ?></a>
								<?php } ?>
								</td>
								<td><?php echo $address->type; ?></td>
								<td class="ltr"><a href="<?php echo $address->destination; ?>" target="_blank"><?php echo $address->destination; ?></a></td>
								<td class="center"><span class="badge"><?php echo $address->hits; ?></span></td>
								<td><span class="<?php echo $statusClass; ?>"><?php echo Translator::trans($statusTxt); ?></span></td>
								<?php
								if($hasButtons){
									echo("<td class=\"center\">".$this->genButtons()."</td>");
								}
								?>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
				<?php $this->paginator(); ?>
			</div>
		</div>
		<div class="modal fade" id="search" tabindex="-1" data-show="true" role="dialog">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo Translator::trans('redirect.search'); ?></h4>
			</div>
			<div class="modal-body">
				<form id="addressSearch" class="form-horizontal" action="<?php echo userpanel\url("settings/redirects"); ?>" method="GET">
					<?php
					$this->setHorizontalForm('sm-3','sm-9');
					$feilds = [
						[
							'name' => 'id',
							'type' => 'number',
							'ltr' => true,
							'label' => Translator::trans("redirect.address.id")
						],
						[
							'name' => 'source',
							'label' => Translator::trans("redirect.address.source"),
							'ltr' => true
						],
						[
							'name' => 'destination',
							'label' => Translator::trans("redirect.address.destination"),
							'ltr' => true
						],
						[
							'name' => 'type',
							'type' => 'select',
							'label' => Translator::trans("redirect.address.type"),
							'options' => $this->getTypeForSelect()
						],
						[
							'name' => 'status',
							'type' => 'select',
							'label' => Translator::trans("redirect.address.status"),
							'options' => $this->getStatusForSelect()
						],
						[
							'name' => 'word',
							'label' => Translator::trans("redirect.address.keyword")
						],
						[
							'type' => 'select',
							'label' => Translator::trans('redirect.search.comparison'),
							'name' => 'comparison',
							'options' => $this->getComparisonsForSelect()
						]
					];
					foreach($feilds as $input){
						$this->createField($input);
					}
					?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" form="addressSearch" class="btn btn-success"><?php echo Translator::trans("redirect.search"); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo Translator::trans('redirect.cancel'); ?></button>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<?php
$this->the_footer();
