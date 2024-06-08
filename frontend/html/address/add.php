<?php
use \packages\base\Translator;
use \packages\userpanel;
$this->the_header();
?>
<div class="row">
	<div class="col-xs-12">
		<form class="redirect-address-add" action="<?php echo userpanel\url("settings/redirects/add"); ?>" method="post">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-plus"></i> <?php echo Translator::trans("redirect.address.add"); ?>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
						<?php
						$this->createField([
							'type' => 'hidden',
							'name' => 'regexr'
						]);
						$this->createField([
							'name' => 'source',
							'label' => Translator::trans('redirect.address.source'),
							'ltr' => true,
							'input-group' => $this->getSourceInputGroup()
						]);
						$this->createField([
							'type' => 'select',
							'name' => 'status',
							'label' => Translator::trans('redirect.address.status'),
							'options' => $this->getStatusForSelect()
						]);
						?>
						</div>
						<div class="col-sm-6">
						<?php
						$this->createField([
							'name' => 'destination',
							'label' => Translator::trans('redirect.address.destination'),
							'ltr' => true
						]);
						$this->createField([
							'type' => 'select',
							'name' => 'type',
							'label' => Translator::trans('redirect.address.type'),
							'options' => $this->getTypeForSelect()
						]);
						?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 pull-left">
							<div class="btn-group btn-group-justified">
								<div class="btn-group">
									<a href="<?php echo userpanel\url('settings/redirects'); ?>" class="btn btn-default"><i class="fa fa-chevron-circle-right"></i> <?php echo Translator::trans("redirect.return"); ?></a>
								</div>
								<div class="btn-group">
									<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> <?php echo Translator::trans("redirect.add"); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
$this->the_footer();
