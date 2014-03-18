<?php

//	get the last 24h
$logs = array_reverse($energyMapper->getGasUsageLogs(24));

$lastLog = $logs[count($logs)-1];
$secondLastLog = $logs[count($logs)-7];

//	get the current
$currentLogs = $energyMapper->getCurrentStats();
$currentLogs = $currentLogs[0];

//	get the records
$recordUsage = $energyMapper->getRecord('gas_usage');

$daily = $energyMapper->getDailyLogs(new DateTime('- 7 days'), new DateTime('today'));
$monthly = $energyMapper->getMonthlyLogs(new DateTime('last year'), new DateTime('next month'));
$yearly = $energyMapper->getYearlyLogs();

$energyUser = $authenticate->getEnergyUser();

?>
        <title>Gas - EnerStats - Statisfy your enery usage</title>    
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
					<li class="active text-center"><a href="/gas"><i class="fa fa-tint"></i><br /><small class="hidden-xs">gas</small></a></li>
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
                            <h1 class="pink">Gas</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <br>
							<h4><?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?> <a href="http://maps.google.com/?q=<?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?>" target="_blank" class="pink"><i class="fa fa-map-marker"></i></a></h4>
                        </div>
                    </div>                    
                </header>
                <hr>           
                <ul class="list-unstyled clearfix">
					<li class="col-md-3"><div class="well text-center">Huidig<br><h4><?php echo number_format($lastLog->getGasUsage() - $secondLastLog->getGasUsage(), 4); ?> m&#179;</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Vandaag<br><h4><?php echo number_format($currentLogs->getGasUsage(), 2); ?> m&#179;</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Dagrecord (<?php echo $recordUsage[0]->getDateCreated()->format('d-m-Y'); ?>)<br><h4><?php echo number_format($recordUsage[0]->getGasUsage(), 2); ?> m&#179;</h4></div></li>					                    					
                    <li class="col-md-3"><div class="well text-center">Totaal<br><h4><?php echo number_format($lastLog->getGasUsage(), 2); ?> m&#179;</h4></div></li>
                </ul>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 24 uur</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="gas-chart-hours" class="chart"></div>
                    </div>					
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 7 dagen</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="gas-chart-days" class="chart"></div>
                    </div>					
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 12 maanden</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="gas-chart-months" class="chart"></div>
                    </div>					
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen jaren</h3>
                        </header>
                    </article>
                    <div class="col-md-4">
                        <div id="gas-chart-years" class="chart"></div>
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
				EnerStats.gasHours = {
					container: 'gas-chart-hours',
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
								html = '<table width="250" class="chart-tooltip"><tr"><td class="light-blue" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B om %H:%M:%S', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' m&#179;</strong></td></tr>';
								});
								html += '</table>';
								return html;
							}						
						},						
						series: [{
						    name: 'Gasverbruik',
						    data: <?php echo $energyMapper->toJson($energyMapper->getDifferences($logs), 'gasUsage'); ?>,
	                    }]
				   }
				};
				EnerStats.gasHours = jQuery.extend(true, {}, EnerStats.defaultAreaChart, EnerStats.gasHours);
				EnerStats.gasHours.init(EnerStats.gasHours.options);
				EnerStats.gasHours.create();		  

				EnerStats.gasDays = {
					container: 'gas-chart-days',
					options: {
						yAxis: { title: { text: 'm3'} },
						colors: ['#fc4349'],
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,  
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="light-blue" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B %Y', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' m&#179;</strong></td></tr>';
								});
								html += '</table>';
								return html;
							}						
						},						
						series: [{
						    name: 'Gasverbruik',
						    data: <?php echo($energyMapper->toJson($daily, 'gasUsage')); ?>,
	                    }]
				   }
				};
				EnerStats.gasDays = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.gasDays);
				EnerStats.gasDays.init(EnerStats.gasDays.options);
				EnerStats.gasDays.create();
				
				EnerStats.gasMonths = {
					container: 'gas-chart-months',
					options: {
						colors: ['#fc4349'],
						yAxis: { title: { text: 'm3'} },
						xAxis: { labels: { formatter: function() { return Highcharts.dateFormat('%B %Y', this.value); } } },
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,  
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="light-blue" colspan="2"><h5>' + Highcharts.dateFormat('%B %Y', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' m&#179;</strong></td></tr>';
								});
								html += '</table>';
								return html;
							}						
						},						
						series: [{
						    name: 'Gasverbruik',
						    data: <?php echo($energyMapper->toJson($monthly, 'gasUsage')); ?>,
	                    }]
				   }
				};
				EnerStats.gasMonths = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.gasMonths);
				EnerStats.gasMonths.init(EnerStats.gasMonths.options);
				EnerStats.gasMonths.create();					
		  
				EnerStats.gasYears = {
					container: 'gas-chart-years',
					options: {
						colors: ['#fc4349'],
						yAxis: { title: { text: 'm3'} },
						xAxis: { labels: { formatter: function() { return Highcharts.dateFormat('%Y', this.value); }}},
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,  
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="light-blue" colspan="2"><h5>' + Highcharts.dateFormat('%Y', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' m&#179;</strong></td></tr>';
								});
								html += '</table>';
								return html;
							}						
						},						
						series: [{
						    name: 'Gasverbruik',
						    data: <?php echo($energyMapper->toJson($yearly, 'gasUsage')); ?>,
	                    }]
				   }
				};
				EnerStats.gasYears = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.gasYears);
				EnerStats.gasYears.init(EnerStats.gasYears.options);
				EnerStats.gasYears.create();
		    });
		</script>