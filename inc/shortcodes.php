<?php

function gff_store_locator_map_shortcode($atts) {
    $maps = get_post_meta($atts['id'], 'maps', true);
    $map_type = get_post_meta($atts['id'], 'map_type', true);

    if(is_array($maps) && !empty($maps)) {
        $html = '<div style="height: 500px;width: 100%" id="map"></div>';


        if($map_type == 'openstreet_map') {
            $html .='<script>
            var map = L.map("map").setView([51.505, -0.09], 13);
        
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                
            }).addTo(map);';

            foreach($maps as $map) {
                $html .='L.marker(['.$map['latitude'].', '.$map['longitude'].']).addTo(map).bindPopup("'.$map['Title'].'");';
            }

            $html .='</script>';
        } else {
            $cs_options = get_option('gff_store_locator_options');
            $html .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$cs_options['api_key'].'&callback=initMap" async defer></script>
            <script >
                let map;
                let InforObj = [];
                let markersOnMap = [';

                foreach($maps as $map) {




                    $html .='{
                        placeName: "'.$map['title'].'",
                        LatLng: [
                            {
                                lat: '.$map['latitude'].',
                                lng: '.$map['longitude'].'
                            }
                        ],
                        infos: [';

                        foreach($map['icon_list'] as $info) {
                            $html .= '{
                                title: "'.$info['title'].'",
                                text: "'.$info['text'].'",
                                icon: "'.$info['icon'].'",
                                link: "'.$info['link']['url'].'",
                            },';
                        }


                        $html .= '
                        ],
                    },';
                }


            $html .='];
            
                console.log(markersOnMap);
                
                window.onload = function() {
                    initMap();
                };

                function addMarker() {
                    var image = {
                        /* marker url */
                        url: "'.plugin_dir_url( __FILE__ ).'../assets/images/marker.png",
                        // This marker is 20 pixels wide by 32 pixels high.
                        size: new google.maps.Size(20, 32),
                        // The origin for this image is (0, 0).
                        origin: new google.maps.Point(0, 0),
                        // The anchor for this image is the base of the flagpole at (0, 32).
                        anchor: new google.maps.Point(0, 32),
                        labelOrigin: new google.maps.Point(0, 42)
                    };
                  
                    /* Create markers loop */
                    for (var i = 0; i < markersOnMap.length; i++) {
                        /* A. Create html data for the markers */
                        var contentString =
                        \'<div class="gff-store-locator-content"><h2>\' +
                        markersOnMap[i].placeName +
                        "</h2>" + 
                        
                        // foreach loop for infos
                        "<div class=\'gff-store-locator-info\'>" +
                        markersOnMap[i].infos.map(function(info) {
                            let infoMarkup = "<p>";
                            
                            if(info.link) {
                                infoMarkup += "<a target=\'_blank\' href=\'" + info.link + "\'>";
                            }
                            
                            if(info.icon) {
                                infoMarkup += "<i class=\'" + info.icon + "\'></i> ";
                            }
                            
                            if(info.title) {
                                infoMarkup += "<strong>" + info.title + "</strong> ";
                            }
                            
                            infoMarkup += info.text;
                            
                            if(info.link) {
                                infoMarkup += "</a>";
                            }
                            
                            infoMarkup += "</p>";
                            
                            return infoMarkup;
                        }).join("") +
                        "</div></div>";
                    
                        /* B. generate markers position and label */
                        const marker = new google.maps.Marker({
                            position: markersOnMap[i].LatLng[0],
                            map: map,
                            icon: image
                        });
                    
                        const infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                    
                        marker.addListener("click", function() {
                            closeOtherInfo();
                            infowindow.open(marker.get("map"), marker);
                            InforObj[0] = infowindow;
                        });
                        
                        google.maps.event.addListener(map, "click", function(event) {
                            infowindow.close();
                        });
                    }
                }

                function closeOtherInfo() {
                    if (InforObj.length > 0) {
                        /* detach the info-window from the marker ... undocumented in the API docs */
                        InforObj[0].set("marker", null);
                        /* and close it */
                        InforObj[0].close();
                        /* blank the array */
                        InforObj.length = 0;
                    }
                }

                function initMap() {
                    map = new google.maps.Map(document.getElementById("map"), {
                    restriction: {
                        latLngBounds: {
                            north: 85,
                            south: -85,
                            west: -180,
                            east: 180
                        }
                    },
                    zoom: 2,
                    disableDefaultUI: true,
                });
                
                addMarker();
                
                var bounds = new google.maps.LatLngBounds();
                
                for (var i = 0; i < markersOnMap.length; i++) {
                    bounds.extend(new google.maps.LatLng(markersOnMap[i].LatLng[0].lat, markersOnMap[i].LatLng[0].lng));
                    }
                    
                    map.fitBounds(bounds);
                }
                
                window.onload = function() {
                    initMap();
                };
            </script>';
        }
    }







    return $html;
}
add_shortcode( 'gff_store_locator', 'gff_store_locator_map_shortcode' );