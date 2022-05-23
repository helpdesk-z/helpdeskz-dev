<?php if (! empty($errors)) : ?>
	<div class="errors" role="alert">
		<ul class="m-0">
		<?php foreach ($errors as $error) : ?>
			<li><?= esc($error) ?></li>
		<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>
