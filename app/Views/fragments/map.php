<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css">



<div id="india-map"></div>

<!-- jsVectorMap JS -->
<script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script> <!-- Use world map -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize jsVectorMap with only India displayed
        new jsVectorMap({
            selector: "#india-map",
            map: "world",
            zoomOnScroll: false,
            zoomButtons: true,
            selectedRegions: ["IN"], // Show only India
            regionStyle: {
                initial: {
                    fill: "#fff"
                }, // Blue color for India
                hover: {
                    fill: "#fff"
                } // Yellow on hover
            },
            focusOn: {
                region: "IN", // Focus on India
                animate: true
            },
            markers: [
                <?php
                $db = db_connect();
                $builder = $db->table('geotags')->where('date', date('Y-m-d'))->orderBy('date', 'DESC');
                $query = $builder->get();
                foreach ($query->getResult() as $row) {
                ?> {
                        name: "<?php echo $row->city; ?>",
                        coords: [<?php echo $row->latitude; ?>, <?php echo $row->longitude; ?>]
                    },

                <?php } ?>
            ],
            markerStyle: {
                initial: {
                    fill: "#6819e6",
                    stroke: "#fff",
                    r: 7
                },
                hover: {
                    fill: "#1cbb8c"
                }
            },
            labels: {
                markers: {
                    render: function(marker) {
                        return marker.name;
                    }
                }
            }
        });
    });
</script>