<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVG Slider</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Slick Slider CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <style>
        /* Basic styling for the slider */
        #svg-slider {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .slick-slide {
            padding: 10px;
        }

        .slick-prev:before, .slick-next:before {
            color: black; /* Arrow color */
        }

        svg {
            width: 100%; /* Scale the SVG to fit the slider */
            height: auto;
        }

        div#loader {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100% !important;
            transform: translate(-50%, -50%);
            background: #fff;
            height: 100vh;
        }

        
		.sidebar {
   
   width: 55px;
}
    </style>
</head>
<body>

    <div id="svg-slider" class="slider"></div>
    <div id="sidebar" class="sidebar"></div>
    <div id="loader" style="display: none; text-align: center;">
        <img src="https://slateblue-goldfish-894680.hostingersite.com/custom-app/loader.gif" alt="Loading..." />
    </div>

    <script>
    $(document).ready(function() {
        var pathArray = window.location.pathname.split('/');
        var folder = pathArray[pathArray.length - 1];
        
        if (!folder) {
            console.error('No folder specified in the URL.');
            return;
        }

        var apiUrl = 'https://slateblue-goldfish-894680.hostingersite.com/custom-app/svgget.php?keyword=' + encodeURIComponent(folder);
        var baseUrl = 'https://slateblue-goldfish-894680.hostingersite.com/custom-app/'; 
        
        // Show the loader before starting the API call
        $('#loader').show(); 
        
        $.getJSON(apiUrl, function(data) {
            var svgSlider = $('#svg-slider');
            var sidebar = $('#sidebar');
            
            // Hide the loader once we receive the response
            $('#loader').hide(); 

            if (data.error) {
                console.error(data.error);
                return;
            }

            // Append main folder SVGs
            if (data.main_folder && data.main_folder.length > 0) {
                data.main_folder.forEach(function(svgFile) {
                    var fullUrl = baseUrl + folder + '/' + svgFile;
                    fetchSVG(fullUrl, svgSlider);
                });
            }

            // Append sidebar SVGs
            if (data.sidebar && data.sidebar.length > 0) {
                data.sidebar.forEach(function(svgFile) {
                    var fullUrl = baseUrl + folder + '/Sidebar/' + svgFile;
                    fetchSVG(fullUrl, sidebar);
                });
            }

            // Initialize the slider after all SVGs are loaded
            setTimeout(function() {
                svgSlider.slick({
                    dots: true,            
                    infinite: true,        
                    slidesToShow: 1,       
                    slidesToScroll: 1,     
                    prevArrow: '<button class="slick-prev">←</button>', 
                    nextArrow: '<button class="slick-next">→</button>'  
                });
            }, 1000); 
        }).fail(function() {
            // Hide the loader in case of error
            $('#loader').hide(); 
            console.error('Failed to fetch SVG data.');
        });

        function fetchSVG(url, container) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.overrideMimeType('image/svg+xml'); 
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var svg = xhr.responseXML.documentElement;
                    var slide = $('<div>').append(svg); 
                    container.append(slide); 
                } else {
                    console.log('Failed to load SVG: ' + url);
                }
            };
            xhr.send();
        }
    });
    </script>

</body>
</html>
