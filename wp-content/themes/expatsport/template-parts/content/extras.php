<?php 
	//For Normal Pages
	$enable_padding = get_sub_field('enable_padding');

    $enable_background_color = get_sub_field('enable_background_color');
	$background_color_fields = get_sub_field('background_color_fields');
	
	$enable_background_image = get_sub_field('enable_background_image');
	$background_image_fields = get_sub_field('background_image_fields');

	$enable_column_fileds = get_sub_field('enable_column_fileds');
	$two_column_values = get_sub_field('two_column_values');

	$max_width = get_sub_field('max_width');

	$border_top = get_sub_field('border_top');


	/** Columns Field **/
	$col1 = ' col-md-6 ';
	$col2 = ' col-md-6 ';
	if($enable_column_fileds):
		if($two_column_values):
			if($two_column_values['column1_value']):
				$col1 = $two_column_values['column1_value'];
			endif;
			if($two_column_values['column2_value']):
				$col2 = $two_column_values['column2_value'];
			endif;
		endif;
	endif;

	/** Padding Field **/
	$paddingClass = '';
	if($enable_padding):
		$paddingClass = ' custom-px ';
	endif;

	/** Background Color Field **/
	$backgroundBGColorStyle = '';
	if($enable_background_color):
		$backgroundBGColorStyle = ' style="background-color:'. $background_color_fields['bg_color'] .'; color:' . $background_color_fields['bg_font_color'] . ';" ';
	endif;

	$maxWidth = '';
	if($max_width && $max_width != 'nowidth'):
		$maxWidth = $max_width;
	endif;

	$sectionClass = '';
	if($border_top):
		$sectionClass = $sectionClass . ' borderTop ';
	endif;


?>