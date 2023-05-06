<?php $index = 0 ?>
<?php foreach ($categories as $category) : ?>
<?= $this->render('@app/views/media/' . ($index++ == 0 ? '_categoryNoPadding' : '_category'), ['category' => $category]) ?>
<?php endforeach ?>