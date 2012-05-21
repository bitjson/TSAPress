<?php


/* TODO: built-in contact form, like: http://www.catswhocode.com/blog/how-to-create-a-built-in-contact-form-for-your-wordpress-theme
	
	can we put in a simple captcha system?
	
	Form should pop out and turn into modal when the user starts filling it out


*/




function tsapress_display_contact_form() { 

	global $tsapress_contact_error, $emailSent, $user_name, $user_email, $user_message;
	
	$contact_email = of_get_option('contact_email', get_option('admin_email')); //defaults to Wordpress admin email
	$contact_info = of_get_option('contact_info', ''); //defaults to nothing

?>
	<section id="contact" class="contact"> <!-- TODO: expand contact form on :focus for ease of use (fill basement?) -->
		<h1>Contact Us</h1><?php
		if($tsapress_contact_error != false) :?>
		<p class="contact-error"><?php echo $tsapress_contact_error ?></p>
		<?php endif;
		if($emailSent) {
			$user_name = "";
			$user_email = "";
			$user_message = "";
		?>
		<p class="contact-success">Your message was sent. Thank you for contacting us!</p>
		<?php } ?>
    	<!-- <p>Please leave us a message, and we'll get back to soon!</p> -->
		<form method="post" action="<?php the_permalink(); ?>#contact">
			<input type="text" id="name" name="name" value="<?php if(isset($user_name)) echo $user_name; ?>" placeholder="Name" required="required" />  
			<input type="email" id="email" name="email" value="<?php if(isset($user_email)) echo $user_email; ?>" placeholder="Email" required="required" /> 
			<textarea id="message" name="message" placeholder="Questions? comments?" required="required" data-minlength="20"><?php if(isset($user_message)) echo $user_message; ?></textarea>   
			<input type="submit" value="Send" id="submit-button" /> 
		</form>
		<address>
		<pre><?php echo $contact_info ?></pre>
		<a href="mailto:<?php echo $contact_email . '?subject=' . rawurlencode(get_bloginfo('name')) ?>"><?php echo $contact_email ?></a>
		</address>
    </section>
		
<?php
}

function tsapress_process_contact_form() {

	if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
		
		isset($_SERVER['HTTP_REFERER']) ? $http_referer = $_SERVER['HTTP_REFERER'] : $http_referer = "None";
		isset($_SERVER['REMOTE_ADDR']) ? $user_ip_address = $_SERVER['REMOTE_ADDR'] : $user_ip_address = "Error retrieving IP.";
		isset($_SERVER['HTTP_USER_AGENT']) ? $user_agent = $_SERVER['HTTP_USER_AGENT'] : $user_agent = "Error retrieving User Agent.";	
		isset($_SERVER['REQUEST_URI']) ? $full_request_url = home_url() . $_SERVER['REQUEST_URI'] : $http_referer = "Error retrieving Request URI.";
		$debugging = false; //set to remove IP address send limit

		
		global $tsapress_contact_error, $emailSent, $user_name, $user_email, $user_message; //uses these variables in tsapress_display_contact_form()
		
		$tsapress_contact_error = false;
		
		$user_name 		= stripslashes_deep(trim($_POST['name']));	//strips slashes because WordPress automatically adds them
		$user_email 	= stripslashes_deep(trim($_POST['email']));
		$user_message 	= stripslashes_deep($_POST['message']);		
		
				
		if(strlen($user_name) < 2) $tsapress_contact_error = "We're sorry, it seems that the name provided is incomplete. Please provide your name and try again.";
		
		if(!is_email($user_email)) $tsapress_contact_error = "We're sorry, it seems the email address you provided is invalid. Please provide a valid email and try again.";
		
		if(strlen($user_message) < 10 ) $tsapress_contact_error = "We're sorry, it seems that your message is incomplete. Please complete your message and try again.";
	
		
		if($tsapress_contact_error === false){
		
			//check for abuse
			$user_transient = 'tsapress_contact_' . substr(md5($user_ip_address), 0, 10);
			$number_of_uses = get_transient($user_transient);
			
			$tester = 'gotten transient: '. $number_of_uses;
			
			($number_of_uses === false) ? $number_of_uses = 1 : $number_of_uses++;
			if($number_of_uses > 2) $tsapress_contact_error = "We're sorry, too many messages have been sent repeatedly from this IP address. Please try again later.";
			
			set_transient($user_transient, $number_of_uses, 60*5 ); //expires in 5 minutes
		
			if($debugging) if($tsapress_contact_error) $tsapress_contact_error .= "… but I'm debugging, so we tried to send it anyway." ;
			
			if($tsapress_contact_error === false OR $debugging){
				
				$email_to_address = get_option('tsapress_contact_email');
				if (!isset($email_to_address) || ($email_to_address == '')) $email_to_address = get_option('admin_email');
				
				$subject = 'New Message: '. home_url('/');
				$headers = 'From: '.$user_name.' <'.$email_to_address.'>' . "\r\n" . 'Reply-To: ' . $user_email;				
				
				$body = "A comment was submitted via the contact form at " . home_url('/') . ". \n\n";

				$body .= "Name: $user_name \nEmail: $user_email\n--\n $user_message \n\n\n";
				
				$footer = "--\nUser Details: \n	IP Address: $user_ip_address \n	Browser: $user_agent\n	Submitted from page: $full_request_url";
				
				if($debugging){
					$footer .= "\n\n--\nDebugging:\n transient:" . $user_transient . ' stored uses value: ' . get_transient($user_transient);
					$footer .= "\nTester: $tester";
				}
				
				$body .= $footer;
				$emailSent = wp_mail($email_to_address, $subject, $body, $headers);
		
				if(!$emailSent){
					$tsapress_contact_error = "We're very sorry, we're having trouble sending your message. We're working to correct the problem as quickly as possible. In the meantime, please email us at <a href=\"mailto:$email_to_address?subject=". rawurlencode($subject) . '&amp;body='. rawurlencode($body) .'">$email_to_address</a>';				
				}
			}
		}		
	}
}