<?php

$logs = array_reverse($energyMapper->getLogs(12*6));
$lastLog = $energyMapper->getLogs(7);
$gasLogs = array_reverse($energyMapper->getGasUsageLogs(12));

$energyUser = $authenticate->getEnergyUser();

?>
        <title>Dashboard - EnerStats - Statisfy your enery usage</title>    
    </head>
    <body>
        <!-- start navigation -->           
        <nav id="navigation" class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/dashboard"><img src="images/logo-enerstats-inverted.png" width="300"></a>
                </div>
				<ul class="nav navbar-nav">
					<li class="active text-center"><a href="/dashboard"><i class="fa fa-dashboard"></i><br /><small class="hidden-xs">dashboard</small></a></li>
					<li class="text-center"><a href="/electra"><i class="fa fa-bolt"></i><br /><small class="hidden-xs">electra</small></a></li>
					<li class="text-center"><a href="/gas"><i class="fa fa-tint"></i><br /><small class="hidden-xs">gas</small></a></li>
					<li class="text-center"><a href="/instellingen"><i class="fa fa-wrench"></i><br /><small class="hidden-xs">instellingen</small></a></li>
					<li class="text-center"><a href="/uitloggen"><i class="fa fa-sign-out"></i><br /><small class="hidden-xs">uitloggen</small></a></li>						
				</ul>
            </div>
        </nav>
        
        <div id="content" class="container">
            <section>
                <header>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <h1 class="pink">Dashboard</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <br>
                            <h4><?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?> <a href="http://maps.google.com/?q=<?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?>" target="_blank" class="pink"><i class="fa fa-map-marker"></i></a></h4>
                        </div>
                    </div>                    
                </header>
                <hr>
                <div class="alert alert-info">
                    <p><strong>Laatste update:</strong> <?php echo $lastLog[0]->getDateCreated()->format('d-m-Y @ H:i:s'); ?>
					<a class="pink pull-right" href="/dashboard"><i class="fa fa-refresh"></i></a></p>
                </div>                                    
                <ul class="list-unstyled clearfix">
                    <li class="col-md-3"><div class="well text-center">Huidig energieverbruik<br><h4><?php echo $lastLog[0]->getCurrentUsage(); ?> kWh</h4></div></li>
                    <li class="col-md-3"><div class="well text-center">Huidig teruglevering<br><h4><?php echo $lastLog[0]->getCurrentRestitution(); ?> kWh</h4></div></li>
                    <li class="col-md-3"><div class="well text-center">Huidig gasverbruik<br><h4><?php echo number_format($lastLog[0]->getGasUsage() - $lastLog[6]->getGasUsage(), 4); ?> m&#179;</h4></div></li>
                    <li class="col-md-3"><div class="well text-center">Tarief<br><h4><?php echo ($lastLog[0]->getRate() == 1) ? 'Dal' : 'Piek'; ?></h4></div></li>
                </ul>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Electriciteit <a href="/electra" class="pink"><i class="fa fa-angle-double-right"></i></a></h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="dashboard-chart-area" class="chart"></div>
                    </div>                        
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Gas <a href="/gas" class="pink"><i class="fa fa-angle-double-right"></i></a></h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="dashboard-chart-area-gas" class="chart"></div>
                    </div>                        
                </div>              
            </section>
            <hr>
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
		<script type="text/javascript">
		    $(function () {
				EnerStats.dashboardEnergy = {
					container: 'dashboard-chart-area',
					options: {
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,
							formatter: function() { 
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B om %H:%M:%S', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' kWh</strong></td></tr>';
								});	
								html += '</table>';
								return html;
							}
						},						
						series: [{
							name: 'Energieverbruik',
	                        data: <?php echo $energyMapper->toJson($logs, 'currentUsage');?>,
	                    },{
	                        name: 'Teruglevering',
	                        data: <?php echo $energyMapper->toJson($logs, 'currentRestitution', true); ?>,
	                    }]    
				   }
				};
				EnerStats.dashboardEnergy = jQuery.extend(true, {}, EnerStats.defaultAreaChart, EnerStats.dashboardEnergy);
				EnerStats.dashboardEnergy.init(EnerStats.dashboardEnergy.options);
				EnerStats.dashboardEnergy.create();

				EnerStats.dashboardGas = {
					container: 'dashboard-chart-area-gas',
					options: {
						colors: ['#fc4349'],
						yAxis: { title: { text: 'm3'} },
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,
							formatter: function() { 
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B om %H:%M:%S', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' m&#179;</strong></td></tr>';
								});	
								html += '</table>';
								return html;
							}
						},						
						series: [{
							name: 'Gasverbruik',
	                        data: <?php echo $energyMapper->toJson($energyMapper->getDifferences($gasLogs), 'gasUsage'); ?>,
	                    }]    
				   }
				};
				EnerStats.dashboardGas = jQuery.extend(true, {}, EnerStats.defaultAreaChart, EnerStats.dashboardGas);
				EnerStats.dashboardGas.init(EnerStats.dashboardGas.options);
				EnerStats.dashboardGas.create();
		    });
		</script>        
    </body>
</html>