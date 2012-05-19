<?php


/* TODO: built-in contact form, like: http://www.catswhocode.com/blog/how-to-create-a-built-in-contact-form-for-your-wordpress-theme
	
	can we put in a simple captcha system?
	
	Form should pop out and turn into modal when the user starts filling it out


*/




function tsapress_display_contact_form() { 

global $tsapress_contact_error; 

$contact_email = of_get_option('contact_email', get_option('admin_email')); //defaults to Wordpress admin email
$contact_info = of_get_option('contact_info', ''); //defaults to nothing

?>


		<h1>Contact Us</h1><?php
		if(!$tsapress_contact_error == false) :?>
		<p class"contact_error"><?php echo $tsapress_contact_error ?></p>
		<?php endif; ?>
    	<!-- <p>Please leave us a message, and we'll get back to soon!</p> -->
		<form method="post" action="<?php the_permalink(); ?>">
			<input type="text" id="name" name="name" value="" placeholder="Name" required="required" />  
			<input type="email" id="email" name="email" value="" placeholder="Email" required="required" /> 
			<textarea id="message" name="message" placeholder="Questions? comments?" required="required" data-minlength="20"></textarea>   
			<input type="submit" value="Send" id="submit-button" /> 
		</form>
		<address>
		<pre><?php echo $contact_info ?></pre>
		<a href="mailto:<?php echo $contact_email . '?subject=' . rawurlencode(get_bloginfo('name')) ?>"><?php echo $contact_email ?></a>
		</address>
		
		
<?php
}

function tsapress_process_contact_form() {

	if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
	
		global $tsapress_contact_error;
		$tsapress_contact_error = false;
		
		$user_name = sanitize_text_field($_POST['name']);
		$user_email = sanitize_text_field($_POST['email']);
		$user_message = wp_kses_data($_POST['message']);
		
		
		if(strlen($user_name) < 2) $tsapress_contact_error = "We're sorry, it seems that the name provided is incomplete. Please provide your name and try again.";
		
		if(!is_email($user_email)) $tsapress_contact_error = "We're sorry, it seems the email address you provided is invalid. Please provide a valid email and try again.";
		
		if(strlen($user_message) < 10 ) $tsapress_contact_error = "We're sorry, it seems that your message is incomplete. Please complete your message and try again.";
	
		
		if($tsapress_contact_error == false){
		
			$email_to_address = get_option('tsapress_contact_email');
			if (!isset($email_to_address) || ($email_to_address == '')) $email_to_address = get_option('admin_email');
			
			$subject = '['. get_bloginfo('name') .'] From '.$user_name;
			$body = "Name: $user_name \n\nEmail: $user_email \n\nMessage: $user_message";
			$headers = 'From: '.$user_name.' <'.$email_to_address.'>' . "\r\n" . 'Reply-To: ' . $user_email;
	
			$emailSent = wp_mail($email_to_address, $subject, $body, $headers);
	
			if(!$emailSent){
				$tsapress_contact_error = "We're very sorry, we're having trouble sending your message. We're working to correct the problem as quickly as possible. In the meantime, please email us at <a href=\"mailto:$email_to_address?subject=".rawurlencode($subject) . '&amp;body='. rawurlencode($body) .'">$email_to_address</a>';				
			}	
		}
		
		if($tsapress_contact_error != false) die($tsapress_contact_error); //TODO: degubbing
		
	}
}