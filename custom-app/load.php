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



  /* Styling the container of buttons */
  .position {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    /* Creating a grid for button arrangement */
    .position {
        display: grid;
        grid-template-columns: 50px 50px 50px;
        grid-template-rows: 50px 50px;
        gap: 10px;
    }

    /* Center the button grid on the page */
    .position {
        width: 160px; /* Width of the entire button section */
        margin: 0 auto;
    }

    /* Styling the buttons */
    .position button {
        width: 50px;
        height: 50px;
        background-color: #9E9E9E; /* Green background */
        color: white;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Hover effect for buttons */
    .position button:hover {
        background-color: #45a049; /* Darker green on hover */
    }

    /* Button positioning */
    #moveLeft {
        grid-column: 1;
        grid-row: 2;
    }

    #moveUp {
        grid-column: 2;
        grid-row: 1;
    }

    #moveDown {
        grid-column: 2;
        grid-row: 2;
    }

    #moveRight {
        grid-column: 3;
        grid-row: 2;
    }
    </style>
</head>
<body>

    <div id="svg-slider" class="slider"></div>
    <div id="sidebar" class="sidebar"></div>
    <div id="loader" style="display: none; text-align: center;">
        <img src="https://slateblue-goldfish-894680.hostingersite.com/custom-app/loader.gif" alt="Loading..." />
    </div>
    <input type="text" id="svgTextInput" placeholder="Enter text">
<div class="position">
    <button id="moveLeft">←</button>
    <button id="moveUp">↑</button>
    <button id="moveDown">↓</button>
    <button id="moveRight">→</button>
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
    
    $('#loader').show(); 

    $.getJSON(apiUrl, function(data) {
        var svgSlider = $('#svg-slider');
        var sidebar = $('#sidebar');
        $('#loader').hide(); 

        if (data.error) {
            console.error(data.error);
            return;
        }

        if (data.main_folder && data.main_folder.length > 0) {
            data.main_folder.forEach(function(svgFile) {
                var fullUrl = baseUrl + folder + '/' + svgFile;
                fetchSVG(fullUrl, svgSlider);
            });
        }

        if (data.sidebar && data.sidebar.length > 0) {
            data.sidebar.forEach(function(svgFile) {
                var fullUrl = baseUrl + folder + '/Sidebar/' + svgFile;
                fetchSVG(fullUrl, sidebar);
            });
        }

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
                setupTextManipulation(svg);
            } else {
                console.log('Failed to load SVG: ' + url);
            }
        };
        xhr.send();
    }

    var currentTextElem;
    var xPos = 0, yPos = 0;

    function setupTextManipulation() {
        currentTextElem = $('.back-center').find('.svgText')[0]; // Find the SVG text element
        $('#svgTextInput').on('input', function() {
            var newText = $(this).val();
            if (currentTextElem) {
                currentTextElem.textContent = newText;
            }
        });

        $('#moveLeft').on('click', function() {
            xPos -= 10;
            moveText();
        });
        $('#moveRight').on('click', function() {
            xPos += 10;
            moveText();
        });
        $('#moveUp').on('click', function() {
            yPos -= 10;
            moveText();
        });
        $('#moveDown').on('click', function() {
            yPos += 10;
            moveText();
        });
    }

    function moveText() {
        if (currentTextElem) {
            currentTextElem.setAttribute('transform', 'translate(' + xPos + ',' + yPos + ')');
        }
    }
});

    </script>

</body>
</html>
