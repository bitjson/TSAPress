<?php

//tsapress_create_class_shortcode("link_list", "link_list");




//usage: [one_half] This content is in a half-width div [/one_half]

function tsapress_create_class_shortcode($shortcode, $class_list = false){
	if ($class_list === false) $class_list = $shortcode;
	$func = create_function( '$atts, $content = null' , 'return "<div class=\"' . $class_list . '\">" . do_shortcode($content) . "</div>";' );
	add_shortcode($shortcode, $func);
}

tsapress_create_class_shortcode("one_half");
tsapress_create_class_shortcode("one_half_last", "one_half last");

tsapress_create_class_shortcode("one_third");
tsapress_create_class_shortcode("one_third_last", "one_third last");
tsapress_create_class_shortcode("two_thirds", "two_thirds");
tsapress_create_class_shortcode("two_thirds_last", "two_thirds last");

tsapress_create_class_shortcode("one_fourth", "one_fourth");
tsapress_create_class_shortcode("one_fourth_last", "one_fourth last");
tsapress_create_class_shortcode("three_fourths", "three_fourths");
tsapress_create_class_shortcode("three_fourths_last", "three_fourths last");


/* ------- Fluid Columns ------- inside style.less / style.css

.one_half{ width:46%; }
.one_third{ width:30%; }
.two_thirds{ width:64%; }
.one_fourth{ width:22%; }
.three_fourths{ width:74%; }

.one_half,.one_third,.two_thirds,.one_fourth, .three_fourths { position:relative; margin-right:4%; float:left; }
.last{ margin-right:0 !important; clear:right; }
.clearboth {clear:both;display:block;font-size:0;height:0;line-height:0;width:100%;}

*/



//admin only notes.
// usage: [note]This is a comment, only visible to administrators.[/note]

function tsapress_note( $atts, $content = null ) {
	 if ( current_user_can( 'publish_posts' ) )
		return '<div class="note">'.$content.'</div>';
	return '';
}
add_shortcode( 'note', 'tsapress_note' );

