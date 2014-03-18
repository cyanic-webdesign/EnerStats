<?php

$energyUser = $authenticate->getEnergyUser();
$post = filter_input_array(INPUT_POST);
$message = false;

if($post) {
	//	exchange post
	$energyUser->exchangeArray($post);
	$energyUser->getDateModified('now');
	
	//	change password
	if($post['password'] == $post['password-repeat'] && $post['password'] != '') {
		$energyUser->setHash($energyUserMapper->createHash($post['password-repeat']));
	}
	
	//	save
	$energyUserMapper->save($energyUser);
	
	$message = true;
}

?>
        <title>Instellingen - EnerStats - Statisfy your enery usage</title>    
    </head>
    <body>
        <!-- start navigation -->           
        <nav id="navigation" class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/dashboard"><img src="images/logo-enerstats-inverted.png" width="300"></a>
                </div>
                <ul class="nav navbar-nav">
					<li class="text-center"><a href="/dashboard"><i class="fa fa-dashboard"></i><br /><small class="hidden-xs">dashboard</small></a></li>
					<li class="text-center"><a href="/electra"><i class="fa fa-bolt"></i><br /><small class="hidden-xs">electra</small></a></li>
					<li class="text-center"><a href="/gas"><i class="fa fa-tint"></i><br /><small class="hidden-xs">gas</small></a></li>
					<li class="active text-center"><a href="/instellingen"><i class="fa fa-wrench"></i><br /><small class="hidden-xs">instellingen</small></a></li>
					<li class="text-center"><a href="/uitloggen"><i class="fa fa-sign-out"></i><br /><small class="hidden-xs">uitloggen</small></a></li>	
				</ul>
            </div>
        </nav>
        
        <div id="content" class="container">
            <section>
                <header>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <h1 class="pink">Instellingen</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <br>
                            <h4><?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?> <a href="http://maps.google.com/?q=<?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?>" target="_blank" class="pink"><i class="fa fa-map-marker"></i></a></h4>
                        </div>
                    </div>                    
                </header>
                <hr>
				<?php if($message === true) : ?>
                <div class="alert alert-info">
                    <p>Je gegevens zijn succesvol aangepast</p>
                </div>
				<?php endif; ?>
				<form class="form-horizontal" method="post" role="form" action="/instellingen">
					<fieldset>
						<h3>Adresgegevens</h3>
						<div class="form-group">
							<label for="address" class="col-sm-2 control-label">Adres</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="address" name="address" value="<?php echo $energyUser->getAddress(); ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="zipcode" class="col-sm-2 control-label">Postcode</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo $energyUser->getZipcode(); ?>" placeholder="1234 AA">
							</div>
							<label for="city" class="col-sm-1 control-label">Woonplaats</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="city" name="city" value="<?php echo $energyUser->getCity(); ?>">
							</div>
						</div>
						<hr>
						<h3>Energieprijzen</h3>
						<div class="form-group">
							<label for="costsEnergyLow" class="col-sm-2 control-label">Electriciteit (Dal)</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="costs_energy_low" name="costs_energy_low" value="<?php echo $energyUser->getCostsEnergyLow(); ?>" placeholder="0.19">
							</div>
							<div class="col-sm-1">
								<small>per kWh</small>
							</div>
							<label for="costsEnergyLow" class="col-sm-2 control-label">Electriciteit (Piek)</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="costs_energy_high" name="costs_energy_high" value="<?php echo $energyUser->getCostsEnergyHigh(); ?>" placeholder="0.21">
							</div>
							<div class="col-sm-1">
								<small>per kWh</small>
							</div>							
						</div>
						<div class="form-group">
							<label for="city" class="col-sm-2 control-label">Gas</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="costs_gas" name="costs_gas" value="<?php echo $energyUser->getCostsGas(); ?>" placeholder="0.19">
							</div>
							<div class="col-sm-2">
								<small>per m&#179;</small>
							</div>
						</div>
						<hr>
						<h3>Inloggegevens</h3>
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label">E-mailadres</label>
							<div class="col-sm-4">
								<input type="email" class="form-control" id="email" name="email" value="<?php echo $energyUser->getEmail(); ?>">
							</div>
						</div>						
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label">Wachtwoord</label>
							<div class="col-sm-4">
								<input type="password" class="form-control" id="password" name="password">
							</div>
						</div>
						<div class="form-group">
							<label for="password-repeat" class="col-sm-2 control-label">Herhaal wachtwoord</label>
							<div class="col-sm-4">
								<input type="password" class="form-control" id="password-repeat" name="password-repeat">
							</div>
						</div>
						<hr>
					    <div class="form-group">
							<label for="submit" class="col-sm-2 control-label">&nbsp;</label>
							<div class="col-sm-2">
								<button type="submit" class="btn btn-lg btn-primary no-border"><i class="fa fa-sign-in"></i> Opslaan</button>
							</div>
				        </div>						
					</fieldset>
				</form>
			</section>
        </div>
        
        <footer id="footer">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-8 credits">
                        <p><small>EnerStats is initiated and created by Cyanic Webdesign &copy; 2014<br>
						Build with <a href="http://getbootstrap.com/" class="light-blue" target="_blank">Twitter Bootstrap</a>,
						<a href="http://fontawesome.io/" class="light-blue" target="_blank">Font Awesome</a> and the lovelyness of
						<a href="http://sass-lang.com/" class="light-blue" target="_blank">SASS</a>. Code licensed under
						<a href="https://github.com/twbs/bootstrap/blob/master/LICENSE" class="light-blue" target="_blank">MIT</a></small>
						</p>
                    </div>					
                    <div class="col-md-4 credits-logo">
                        <img width="150" src="images/logo-enerstats-inverted-small.png" alt="EnerStats"><h3 class="tagline">Statisfy your energy usage</h3>
                    </div>
                </div>
            </div>
        </footer>
        
		<!-- copyright -->
		<div class="cwcopyright">
			<a href="http://www.cyanicwebdesign.nl" title="Cyanic Webdesign - Staphorst" target="_blank"><img src="images/copyright-cyaniwebdesign.png" /></a>
		</div>
		<!-- end copyright -->	        
        
        <!-- scripts -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
   
    </body>
</html>