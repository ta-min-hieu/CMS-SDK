<?php $view = Yii::$app->request->get('view', null); ?>
<?php if($view == 1 && count($errorArr)): ?>
	<div class="alert alert-warning" role="alert">
	<?php foreach($errorArr as $err): ?>
		<div class="err-msg"><?= $err; ?></div>
	<?php endforeach; ?>
	
	</div>
<?php endif;?>