<div class="media">
	<?php echo $type['type'] == 1 ? icon('fas fa-crosshairs') : icon('far fa-calendar') ?>
	<div class="media-body">
		<?php echo $title ?>
		<?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])).' '.icon('far fa-clock').timetostr('%H:%M', $date) ?>
		<?php if ($description): ?>
		<div class="mt-3">
			<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($description))), 90) ?>
		</div>
		<?php endif ?>
	</div>
</div>
