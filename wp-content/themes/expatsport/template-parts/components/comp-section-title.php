<?php
	$headingType = isset($args['headingType']) ? $args['headingType'] : 'h2';
	$title = isset($args['title']) ? $args['title'] : '';
	$class = isset($args['class']) ? $args['class'] : '';
?>
<div class="section-title-wrapper">
	<?php if($headingType == 'h1'): ?>
		<h1 class="h2-style section-title section-title--with-line anim <?php echo $class; ?>">
			<?php echo $title; ?>
		</h1>
	<?php else: ?>
		<h2 class="section-title section-title--with-line anim <?php echo $class; ?>">
			<?php echo $title; ?>
		</h2>
	<?php endif; ?>
</div>