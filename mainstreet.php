<!DOCTYPE html>
<!-- Template by quackit.com -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Main Street App - Wharton</title>
		<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.2.js'></script>
		<script type="text/javascript" src="../canvasjs/jquery.canvasjs.min.js" defer></script>
		<style type="text/css">
		body{font:14px verdana; color:black }
		
		p.normal {
			font-variant: normal;
		}

		p.small {
			font-variant: small-caps;
		}
		main {
			position: fixed;
			top: 65px; /* Set this to the height of the header */
			bottom: 35px; /* Set this to the height of the footer */
			left: 100px; 
			right: 0;
			overflow: auto; 
			background: #fff;
		}
				
		#header {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 65px; 
			overflow: hidden;
			background: #120A2A;
		}

		#footer {
			position: absolute;
			left: 0;
			bottom: 0;
			width: 100%;
			height: 35px; 
			overflow: hidden;
			background: #120A2A;
		}
				
		#left {
			position: absolute; 
			top: 65px; /* Set this to the height of the header */
			bottom: 35px; /* Set this to the height of the footer */
			left: 0; 
			width: 100px;
			overflow: auto; 
			background: #acacac; 		
		}

		#right {
			position: absolute; 
			top: 50px; /* Set this to the height of the header */
			bottom: 50px; /* Set this to the height of the footer */
			right: 0; 
			width: 400px;
			overflow: auto; 
			background: #F7FDEB; 		
		}
		.accItem{
			background-color:#efefef;
			background-position:0px 6px;
			display:none;
			border:1px solid #999;
			margin: 3px 0px 3px 0px;
		}

		.accItemSel{
			background-position:0px 6px;
			border:1px solid #006;
			background-color:#fff;
			margin: 3px 0px 3px 0px;
		}
		.accItem h2{
			margin: 5px 0px 5px 0px;
			background-position:left top;
			padding-left:30px;
			color:#666;
			font-size:20pt;
			cursor:pointer;    
		}

		.accItemSel h2{
			margin: 5px 0px 5px 0px;
			background:;
			background-position:left top;
			padding-left:30px;
			color:#006;
			font-size:20pt;
		}
		.accItem h2:hover{
			color:#600;
		}

		.accItem .desc, .accItemSel .desc{
			display:none;
			margin: 5px 0px 5px 20px;
			padding: 10px 0px 10px 30px;
		}
						
		.innertube {
			margin: 15px; /* Provides padding for the content */
		}
		
		p {
			color: #555;
		}

		nav ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
		}
		
		nav ul a {
			color: darkgreen;
			text-decoration: none;
		}
		
		table {
			border-collapse: collapse;
		}
		
		table, th, td {
			border: 0px solid green;
		}
		
		/*IE6 fix*/
		* html body{
			padding: 50px 200px 50px 200px; /* Set the first value to the height of the header, the second value to the width of the right column, third value to the height of the footer, and last value to the width of the left column */
		}
		
		* html main{ 
			height: 100%; 
			width: 100%; 
		}
	</style>
	<script type='text/javascript'>//<![CDATA[ 
		$(window).load(function(){
		//jAccordion with More Info
		(function( $ ){
			$.fn.jAccordion = function() {
					showItems('');
					$(this).find('h2').live('click', function(){
						var that=$(this).parent();
						
						$('.accItemSel').addClass('accItem');
						$('.accItemSel').removeClass('accItemSel');
										
						$('.accItem .desc:visible').slideUp('fast',function(){
							$(that).find('.desc').slideDown('fast');
							$(that).removeClass('accItem');
							$(that).addClass('accItemSel');
						});                    
					});        
				
				$(this).find('.desc .more').click(function(){
					var that2=this;
					$(this).hide();
					$(this).next('div').slideDown('medium');
			 
				});
			};

			function showItems(def){
				if ($('.accItem').not(':visible').length>0){
					$($('.accItem').not(':visible')[0]).fadeIn('medium',function(){
						showItems(def);
					});
				}else{
					var p = null;
					if (def.length>0)
						p=$('.accItem#'+def);                                    
					else
						p=$('.accItem:first');
										
					$(p).addClass('accItemSel');
					$(p).removeClass('accItem');
					$(p).find('h2').css('background-image', 'none');
					$(p).find('.desc').slideDown('fast');    
				}
			}
			
		})( jQuery );

		$('.accItem').jAccordion();

		});//]]>  
	</script>
	</head>
<body>	
	<header id="header">
			<div class="innertube">
			<table width=100%><tr><td align=left><font color=white><h2>Main$treet</h2></font></td><td align=right>			
				<FORM NAME="form2" METHOD="POST" ACTION="">
					<INPUT TYPE="Text" NAME="Username">
					<INPUT TYPE = "Submit" Name = "UserName" VALUE = "Refresh">
				</FORM>
				
				<?php
				if (isset($_POST['Username'])) $thisuser = $_POST['Username'];
				else 
					$thisuser = "";

				include_once('class.accessdb.php');
				include_once('class.stocks.php');	
				
				$objDatabase = new Customers;	
				$objDatabase->init("localhost", "customers", "testuser", "test123");
				$objDatabase->connect();

				$stocks = array();	
				//
				// $thisusername = full user name of $thisuser
				// $portfolio => double array of portfolio for $thisuser (stock sym, stock name, stock weight)
				// $stocks => single array of all stocks in $thisuser's portfolio
				// $friends => double array of all friends for $thisuser (fullname and username of each friend)
				//
				$thisusername = $objDatabase->fullusername("username", $thisuser);
				$portfolio = $objDatabase->retrieveportfolio($thisuser);
				foreach ($portfolio as $count => $thisstock) $stocks[] = $thisstock[0];
				$friends = $objDatabase->retrievefriends($thisuser);
				
				// echo "Welcome ".$thisusername."!";
				
				// stock information for all stocks in $thisuser portfolio
				$objYahooStock = new YahooStock;
				/**
					s = Symbol
					n = Name
					l1 = Last Trade (Price Only)
					d1 = Last Trade Date
					t1 = Last Trade Time
					c = Change and Percent Change
					v = Volume
					c1 = change
					p2 = percent change
				*/
				$objYahooStock->addFormat("sl1p2");
				foreach ($stocks as $count => $curstock) $objYahooStock->addStock($curstock);
				
				?>
			</td></tr></table>
			</div>
		</header>
				
		<footer id="footer">
			<div class="innertube">
				<font color=white><p>Main Street App</p></font>
			</div>
		</footer>			

		<nav id="left">
			<div class="innertube">
				<p align=right>Settings</p>
				<p align=right>Dashboard</p>
				<p align=right>About</p>
				<p align=right>Contact</p>
			</div>
		</nav>	

		<main>
			<div class="innertube">
				<!-- FIRST ITEM -->
				<div class="accItem" id="myportfolio">
					<h2>My portfolios</h2>
					<div class='desc'>
						<?php
							
						// Returns an array with {0}=change in portfolio, {1}=string for complete output
						function processportfolio($objYahooStock, $pf)
						{	
							$arraypf = array();
							$stringoutput = ""; 
							$thispfchange = 0;
							$stringoutput = $stringoutput."<table width=50%><tr><th align=left>Ticker</th><th align=right>Value</th><th align=right>Change</th></tr>";
							
							foreach ($objYahooStock->getQuotes() as $code => $stock)
							{
								$stringoutput = $stringoutput."<tr><td align=left>".strtoupper(str_replace('"',"", $stock[0]))."</td>";
								
								$imagegif = "nochange.jpg";
								$change = trim(str_replace('"',"", $stock[2]));
								if ((float)$change < 0) $imagegif = "down.jpg"; else if ((float)$change > 0)$imagegif = "up.jpg";
								$stringoutput = $stringoutput."<td align=right>$".$stock[1]."</td><td align=right>".$change." <img src=$imagegif height=12 width=12></td></tr>";
								
								foreach ($pf as $count => $thisweight)
								{
									if ($thisweight[0] === str_replace('"',"", $stock[0]))
									{
										$thischange = (float) trim(str_replace('"', "", $stock[2]));
										$weight = $thisweight[2];
										
										$thispfchange += $thischange * (float)($weight/100);
										break;
									}
								}
							}
							$stringoutput = $stringoutput."</table>";
							
							// Returns an array with {0}=change in portfolio, {1}=string for complete output
							$arraypf[] = $thispfchange;
							$arraypf[] = $stringoutput;
							
							return $arraypf;
						}
						$stockinfo = processportfolio($objYahooStock, $portfolio);
						echo $stockinfo[1]; // complete string for output to display 
						echo $stockinfo[0]."%"; // computed portfolio change
						
					?>	
					</div>
				</div>

				<!-- SECOND ITEM -->
				<div class="accItem" id="mynews">
					<h2>My news</h2>
					<div class='desc'>
						<?php 
						$feed = $objYahooStock->getUserNews(); 
						
						$limit = 15;
						echo "<table width=75%><tr align=top><td align=left>";
						for($x=0;$x<$limit;$x++) 
						{
							$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
							$link = $feed[$x]['link'];
							$description = $feed[$x]['desc'];
							$date = date('l F d, Y', strtotime($feed[$x]['date']));
							echo '<a href="'.$link.'" title="'.$date."\n".$description.'"><font size=2>'.$title.'</a>'."</font><br>";			
						}		
						echo "</td></tr></table>";
						?>
					</div>
				</div>

				<!-- THIRD ITEM -->
				<div class="accItem" id="mytrackedportfolios">
					<h2>My Friends</h2>
					<div class='desc'>
					
						<?php
							//
							// Print each of $thisuser friend's portfolio
							//
							$friendspf = array();
							$processedinfo = array();
							// first entry in $friendspf is the current user themselves
							$friendspf[] = array($thisuser, $portfolio, $stockinfo[0], $stockinfo[1]);
							
							foreach ($friends as $count => $thisfriend)
							{
								$stocks = array();
								
								$objFriendYahoo = new YahooStock;	
								$pf = $objDatabase->retrieveportfolio($thisfriend[1]);
								foreach ($pf as $count => $thisstock) $stocks[] = $thisstock[0];
								
								$objFriendYahoo->addFormat("sl1p2");
								foreach ($stocks as $count => $curstock) $objFriendYahoo->addStock($curstock);
								
								$processedinfo = processportfolio($objFriendYahoo, $pf);
								
								$friendspf[] = array($thisfriend[1], $pf, $processedinfo[0], $processedinfo[1]);
							}
							//
							// $friendspf is a double array all of friends of the current user in the format:(friendusername, friendportfolio, pfchange, completeoutstr). 
							// The first entry is the current user.
							//
							// print_r($friendspf);
							//
						?>
						<div id="chartContainer" style="height: 300px; width: 50%;"></div>
						<p id="friendspf"></p>
						
						<script type="text/javascript">
							window.onload = function () {
								var chart = new CanvasJS.Chart("chartContainer", {
									theme: "theme1",
									title:{
										text: "My tracked portfolios"             
									},
									animationEnabled: true,   // change to true
									data: [              
									{
										// Change type to "bar", "splineArea", "area", "spline", "pie",etc.
										type: "column",
										click: onClick,
										dataPoints: [
											<?php
												foreach ($friendspf as $count => $thispf)
												{
													echo '{ label: "'.$thispf[0].'", y: '.$thispf[2].'  },'."\n";
												}
											?>
											]
									}
									]
								});
								chart.render();
											
								function onClick(e) {
									<?php
										$completeportfoliostr = "";
										foreach ($friendspf as $count => $thispf)
										{
											$completeportfoliostr = $completeportfoliostr."<br>-----------------<br>";
											$completeportfoliostr = $completeportfoliostr."Friend: ".$thispf[0]."<br>";
											$completeportfoliostr = $completeportfoliostr.$thispf[3]; 
											$completeportfoliostr = $completeportfoliostr.$thispf[2]."%";
											$completeportfoliostr = $completeportfoliostr."<br>-----------------<br>";
										}
										
										echo 'document.getElementById("friendspf").innerHTML ="'.$completeportfoliostr.'";';
									?>
								}
							}
						</script>

					</div>
				</div>    

				<!-- FOURTH ITEM -->
				<div class="accItem" id="otherstuff">
					<h2>Other stuff - future</h2>
					<div class='desc'>
					To be decided
					</div>
				</div>            			
			</div>
		</main>		
	</body>
</html>