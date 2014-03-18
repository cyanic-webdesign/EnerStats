<?php

use Cyanic\Model\EnergyUser as EnergyUserModel;

//	initiate
$error = false;

//  get the post
$post = filter_input_array(INPUT_POST);
if($post && isset($post['input-email']) && isset($post['input-password'])) {	
    if($post['input-email'] == '' || $post['input-password'] == '') {
		$error = true;
	} else {		
		$energyUser = $energyUserMapper->fetchRow("email = :email", array('email' => $post['input-email']));
		if($energyUser && $energyUserMapper->validate($energyUser, $post['input-password'])) {
			$energyUser->setDateLastLogin('now');
			$energyUserMapper->save($energyUser);		
			$authenticate->authenticate($energyUser, true);
			header('Location: /dashboard');
		}    
		$error = true;		
	}
}
?>

        <title>Inloggen - EnerStats - Statisfy your enery usage</title>    
    </head>
    <body class="blue">
        <div class="container-fluid center">       
            <section class="widget-login">
                <header>
	                <img src="images/logo-enerstats.png" width="300" class="img-responsive pull-right" alt="EnerStats">
	                <h3 class="tagline text-right">Statisfy your energy usage</h3>
                </header>               
                <br>
                <form class="form-horizontal"  method="post" action="/inloggen" role="form">
                    <fieldset>
	                    <div class="input-group <?php if($error) : ?>has-error<?php endif; ?> ">
	                        <span class="input-group-addon square-border"><strong>@</strong></span>
	                        <input type="email" class="form-control input-lg square-border" name="input-email" id="input-email" value="<?php echo ($post) ? $post['input-email'] : ''; ?>" placeholder="E-mailadres (info@domein.nl)">		              		        
					    </div>
						<br>
	                    <div class="input-group <?php if($error) : ?>has-error<?php endif; ?> ">
	                        <span class="input-group-addon square-border"><i class="fa fa-cog"></i></span>
					        <input type="password" class="form-control input-lg square-border" name="input-password" id="input-password" placeholder="Wachtwoord">
					    </div>
						<br>
					    <div class="form-group">
	                        <button type="submit" class="btn btn-lg btn-primary no-border"><i class="fa fa-sign-in"></i> Inloggen</button>
				        </div>
                    </fieldset>                    
                </form>
                <hr>
                    <a href="http://www.github.com/cyanic-webdesign" target="_blank" class="btn btn-lg btn-primary no-border"><i class="fa fa-github"></i></a>
                    <a href="http://www.facebook.com/cyanicwebdesign" target="_blank" class="btn btn-lg btn-primary no-border"><i class="fa fa-facebook"></i></a>
                    <a href="http://www.cyanicwebdesign.nl/" target="_blank" class="btn btn-lg btn-primary no-border"><i class="fa fa-bookmark"></i></a>           
                <hr> 
                <p class="lightGrey"><small>EnerStats is initiated and created by Cyanic Webdesign &copy; <?php echo (date('Y') > 2014) ? '2014-' . date('Y') : '2014'; ?></small></p>
            </section>           
        </div>
		
		<script>
			$(function() {$('html').addClass('blue'); });			
		</script>		