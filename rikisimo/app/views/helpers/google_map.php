<?php
/*
 * CakeMap -- a google maps integrated application built on CakePHP framework.
 * Copyright (c) 2005 Garrett J. Woodworth : gwoo@rd11.com
 * rd11,inc : http://rd11.com
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.1311_pre_beta
 * @license     OPPL
 *
 *
 * Modified by 	Ivan Ribas <ivanrise@gmail.com>
 * Date			Nov 7, 2008
 */

class GoogleMapHelper extends Helper {

	var $errors = array();
	var $helpers = array('Html','Javascript', 'RfRating');
	var $googleJS = false;

  function jsLink() {
    $googleMapKey = Configure::read('appSettings.googleMapKey');
    $this->Javascript->link('http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$googleMapKey,false);
    $this->googleJS = true;
  }
  
	function map($default, $style = 'width: 400px; height: 400px' ) {
  
    if(!$this->googleJS) $this->jsLink();
    
		//if (empty($default)){return "error: You have not specified an address to map"; exit();}
		$out = "<div id=\"streetview\" style=\"height:250px;display:none\"></div>";
		$out .= "<div id=\"map\" ";
		$out .= isset($style) ? "style=\"".$style.";display:block\"" : null;
		$out .= " ></div>";
		$out .= "
		<script type=\"text/javascript\">
		//<![CDATA[

		 function toggle_visibility(id) {

       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }

		if (GBrowserIsCompatible()) 
		{	
			
						var icon = new GIcon();
						icon.image = 'http://labs.google.com/ridefinder/images/mm_20_green.png';
						icon.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
						icon.iconSize = new GSize(12, 20);
						icon.shadowSize = new GSize(22, 20);
						icon.iconAnchor = new GPoint(6, 20);
						icon.infoWindowAnchor = new GPoint(5, 1);
								
						var n_icon = new GIcon();
						n_icon.image = 'http://labs.google.com/ridefinder/images/mm_20_gray.png';
						n_icon.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
						n_icon.iconSize = new GSize(12, 20);
						n_icon.shadowSize = new GSize(22, 20);
						n_icon.iconAnchor = new GPoint(6, 20);
						n_icon.infoWindowAnchor = new GPoint(5, 1);
						
			var map = new GMap(document.getElementById(\"map\"));
			map.addControl(new GLargeMapControl3D());
	//		map.addControl(new GMapTypeControl());
			map.setMapType(map.getMapTypes()[".$default['type']."]);
			map.centerAndZoom(new GPoint(".$default['long'].", ".$default['lat']."), ".$default['zoom'].");
		}
		//]]>
		</script>";

		return $out;
	}

	function addMarkers(&$data, $icon=null)
	{
		$out = "
			<script type=\"text/javascript\">
			//<![CDATA[
			if (GBrowserIsCompatible()) 
			{
			";
			
			if(is_array($data))
			{
				if($icon)
				{
					$out .= $icon;		
				}
				else
				{
					$out .= 'var icon = new GIcon();
						icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
						icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
						icon.iconSize = new GSize(12, 20);
						icon.shadowSize = new GSize(22, 20);
						icon.iconAnchor = new GPoint(6, 20);
						icon.infoWindowAnchor = new GPoint(5, 1);
					';

				}
				$i = 0;
				foreach($data as $n=>$m){
					$keys = array_keys($m);
					$point = $m[$keys[0]];
					if(!preg_match('/[^0-9\\.\\-]+/',$point['longitude']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['longitude'])
						&& !preg_match('/[^0-9\\.\\-]+/',$point['latitude']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['latitude']))
					{
						$out .= "
							var point".$i." = new GPoint(".$point['longitude'].",".$point['latitude'].");
							var marker".$i." = new GMarker(point".$i.",icon);
							map.addOverlay(marker".$i.");
							marker$i.html = \"$point[title]$point[html]\";
							GEvent.addListener(marker".$i.", \"click\", 
							function() {
								marker$i.openInfoWindowHtml(marker$i.html);
							});";
						$data[$n][$keys[0]]['js']="marker$i.openInfoWindowHtml(marker$i.html);";
						$i++;
					}
				}
			}
		$out .=	"} 
				//]]>
			</script>";
		return $out;
	}
	
	function addClick($var, $script=null)
	{
		$out = "
			<script type=\"text/javascript\">
			//<![CDATA[
			if (GBrowserIsCompatible()) 
			{
			" 
			.$script
			.'GEvent.addListener(map, "click", '.$var.', true);'
			."} 
				//]]>
			</script>";
		return $out;
	}	
	
	function addMarkerOnClick($innerHtml = null)
	{
		$mapClick = '
			var mapClick = function (overlay, point) {
				var point = new GPoint(point.x,point.y);
				var marker = new GMarker(point);
				map.addOverlay(marker)
				GEvent.addListener(marker, "click", 
				function() {
					marker.openInfoWindowHtml('.$innerHtml.');
				});
			}
		';
		return $this->addClick('mapClick', $mapClick);
		
	}	
	
	
	function showAddress($address) {
    
	  $code = '
	     <script type="text/javascript">
    //<![CDATA[

   var addressMarker;
    
   geocoder = new GClientGeocoder();

      if (geocoder) {
        geocoder.getLatLng("'.$address.'",
          function(point) {
            if (!point) {
				$("view_map_full").hide();
				/*
               map.setCenter(new GLatLng(37.4419, -122.1419), 13);
               map.openInfoWindow(map.getCenter(),
                           "Sorry, I don\'t find the address:<br/>"+\''.Sanitize::clean($address).'\');
*/
            } else {
              if (addressMarker) {
                map.removeOverlay(addressMarker);
              }
              addressMarker = new GMarker(point, icon);
              map.setCenter(point);
 					    var lat = point.y;
					    var lng = point.x;

              map.addOverlay(addressMarker); 
              
            }
          }
        );
      }
      		//]]>
		</script>
	  ';
	  return $code;
	}

	function showLatLng($lat,$lng) {
	  $code = '
	     <script type="text/javascript">
    //<![CDATA[
              var point = new GLatLng('.$lat.','.$lng.');
              var addressMarker = new GMarker(point, icon);
              map.setCenter(point);
 					    map.setZoom(15);
              map.addOverlay(addressMarker); 
      		//]]>
		</script>
	  ';
	  return $code;
	}

	function showLatLngEdit($lat,$lng) {
	  $code = '
	     <script type="text/javascript">
    //<![CDATA[
              var point = new GLatLng('.$lat.','.$lng.');
              var addressMarker = new GMarker(point,{draggable: true});
                      				  
            GEvent.addListener(addressMarker, "dragstart", 
                                function() {
                                  map.closeInfoWindow();
                                }
                              );

            GEvent.addListener(addressMarker, "dragend", 
                                function() {
                                  point = addressMarker.getPoint();
                       					  lat = point.y;
	                      				  lng = point.x;
                                   
                                  $("NodeLat").value=lat;
                                  $("NodeLng").value=lng;

                                }
                              );

 					    map.setZoom(15);
                                
                                
            map.setCenter(point);
            point = addressMarker.getPoint();
 					  var lat = point.y;
					  var lng = point.x;

            $("NodeLat").value=lat;
            $("NodeLng").value=lng;
										  					
            map.addOverlay(addressMarker); 
            
            GEvent.addListener(map, "click", 
							function(overlay, latlng) {
							  if(typeof(addressMarker)=="undefined") {
								  showAddress(document.getElementById("NodeAddress").value+", "+document.getElementById("CityName").value);
								}
								else {
								  addressMarker.setLatLng(latlng);
								}
								
							});
      		//]]>
		</script>
	  ';
	  return $code;
	}
	
	function showAddressEdit() {
    
	  $code = '
	     <script type="text/javascript">
    //<![CDATA[

   var addressMarker;
    
   geocoder = new GClientGeocoder();
   function showAddress(address) {
   
   
      if (geocoder) {
        geocoder.getLatLng(address,
          function(point) {
              if (addressMarker) {
                map.removeOverlay(addressMarker);
              }
            if (!point) {
              point = map.getCenter();
            } else {
                map.setZoom(15);
            }
            addressMarker = new GMarker(point,{draggable: true});

            GEvent.addListener(addressMarker, "dragstart", 
                                function() {
                                  map.closeInfoWindow();
                                }
                              );

            GEvent.addListener(addressMarker, "dragend", 
                                function() {
                                  point = addressMarker.getPoint();
                       					  lat = point.y;
	                      				  lng = point.x;
	                      				  
                                  $("NodeLat").value=lat;
                                  $("NodeLng").value=lng;
                                }
                              );
                                
            map.setCenter(point);
            point = addressMarker.getPoint();
 					  var lat = point.y;
					  var lng = point.x;

            $("NodeLat").value=lat;
            $("NodeLng").value=lng;
										  					
            map.addOverlay(addressMarker); 
            
                                  
          }
        );
      }
      }


   					GEvent.addListener(map, "click", 
							function(overlay, latlng) {
							  if(typeof(addressMarker)=="undefined") {
								  showAddress(document.getElementById("NodeAddress").value+", "+document.getElementById("CityName").value);
								}
								else {
								  addressMarker.setLatLng(latlng);
								}
								
							});


      		//]]>
		</script>
	  ';
	  return $code;
	}
	
	function addStreetView() {

		$out = "";// "<div id=\"streetView\" style=\"height:400px;\"></div>";
		$out .= "
		<script type=\"text/javascript\">
		//<![CDATA[

		if (GBrowserIsCompatible()) 
		{
		
		streetDivVisible = false;
		
    function showPanoData(panoData) {
      if (panoData.code != 200) {
     //   GLog.write('showPanoData: Server rejected with code: ' + panoData.code);
        return;
      }
      toggle_visibility('streetViewLink');
      myPano.setLocationAndPOV(panoData.location.latlng);
    }

    function showStreet() {
		panoClient = new GStreetviewClient();      
    	panoClient.getNearestPanorama(addressMarker.getLatLng(), showPanoData);

    	myPano = new GStreetviewPanorama(document.getElementById(\"streetview\"));
	}
		
		}
		
		if(addressMarker) showStreet();

		      		//]]>
		</script>
		";
		$out .= $this->Html->link(__('Street view',true),'#',array('id'=>'streetViewLink','style'=>'display:none','onclick'=>'toggle_visibility("map");toggle_visibility("streetview");
		if(streetDivVisible==false) {myPano.checkResize();streetDivVisible=true}
		;return false;'));

		return $out; 	
	}
	
	function addNearMarker($node, $city_slug) {
		$tags = array_slice($node['Tag'], 0, 2);
		$i['units'] = 5;
		$i['unit_width'] = 25;
		$i['voted'] = true;
		$i['votes'] = $node[0]['Votes'];
		$i['id'] = $node['Node']['id'];
		$i['rating'] = $node[0]['totalPoints'];
		$i['rating_value'] = @number_format($i['rating']/$i['votes'], 2);
		$out = '
			<script type="text/javascript">
			//<![CDATA[
		  var point = new GLatLng('.$node['Node']['lat'].','.$node['Node']['lng'].');
          var nearMarker'.$node['Node']['id'].' = new GMarker(point, n_icon);
          map.addOverlay(nearMarker'.$node['Node']['id'].');
		GEvent.addListener(nearMarker'.$node['Node']['id'].', "click", 
		function() {
			nearMarker'.$node['Node']['id'].'.openInfoWindowHtml(\''.$this->Html->link($node['Node']['name'], array('action'=>'view', $city_slug, $node['Node']['slug'])).$this->RfRating->ratingBar($i).'<div class="general_info"> '.$node['Node']['address'];
		if($node['Category']['value']) {
			$out.= '<p>'.__('Category', true).': '.$this->Html->link($node['Category']['value'], array('controller'=>'nodes', 'action'=>'index', $node['City']['slug'], $node['Category']['slug'])).'</p>';
		}
			if(!empty($tags)) {
				$out.= '<p>';
				$out.=__('Tags', true).': ';
				$n=1;
				foreach($tags as $tag) {
					$out.=$this->Html->link(ucfirst($tag['name']), array('controller'=>'nodes', 'action'=>'index', 'all-cities', 'all-categories', $tag['slug']));
					if($n<count($tags)) $out.= ", ";
					else $out.= ".";
					$n++;
				}
				$out.='</p>';
			}
			$out.='</div>\');
		});		
		      		//]]>
		</script>	
		';
		return $out;
	}
	
}
?>