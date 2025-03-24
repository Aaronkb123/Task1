"use strict"; // using strict mode
/*Cookie set*/
var r = document.querySelector(':root'); // get the root element
var links = r.getElementsByTagName('a'); // get all links on the page
var textFont = ""; // variable to store the font family of the text
var fontSize = 0; // variable to store the font size of the text
document.addEventListener("DOMContentLoaded", initialisation); // wait for the page to load. Then call the initialisation function.

function initialisation() { // function to initialise the page with all accessibility settings in place.
    document.getElementById("accessibility").style.width = "0"; // hide the accessibility menu
    document.getElementById("accessibility_main").style.marginLeft= "0"; // hide the accessibility menu
    if (getCookie("highContrast",'nope') === "nope"||getCookie("highContrast",'nope') === "false") { //This checks if the high contrast mode is on. The code will get the cookies, and change the background and text color accordingly.
        //High contrast mode is off
        highContrast = false; 
        r.style.setProperty('--background', '#f4f4f4');
        r.style.setProperty('--text-black', '#000000');
        r.style.setProperty('--text', '#f4f4f4');
        r.style.setProperty('--high-contrast-black', 'transparent');
        r.style.setProperty('--box1', '#e0f7fa');
        r.style.setProperty('--box2', '#ffe0b2');
        r.style.setProperty('--box3', '#f8bbd0');
        r.style.setProperty('--box4', '#d1c4e9');
        r.style.setProperty('--button', '#0fbfe4');
        r.style.setProperty('--button-pressed', '#000000');
        r.style.setProperty('--a', '#ffffff');
        r.style.setProperty('--a2', '#000000');
        r.style.setProperty('--a-clicked', '#0fbfe4');
        r.style.setProperty('--a-decoration', 'none');
        r.style.setProperty('--hover', '#555');
        r.style.setProperty('--table-border', '#000000');
    } else {
        //High contrast mode is on
        highContrast = true;
        r.style.setProperty('--background', '#000000');
        r.style.setProperty('--text-black', '#ffff00');
        r.style.setProperty('--text', '#ffff00');
        r.style.setProperty('--high-contrast-black', '#000000');
        r.style.setProperty('--box1', '#0000FF');
        r.style.setProperty('--box2', '#0000FF');
        r.style.setProperty('--box3', '#0000FF');
        r.style.setProperty('--box4', '#0000FF');
        r.style.setProperty('--button', '#00ffff');
        r.style.setProperty('--button-pressed', 'transparent');
        r.style.setProperty('--a', '#ff00ff');
        r.style.setProperty('--a2', '#ff00ff');
        r.style.setProperty('--a-clicked', '#00ffff');
        r.style.setProperty('--a-decoration', 'solid');
        r.style.setProperty('--hover', '#000000');
        r.style.setProperty('--table-border', '#ff00ff');
    }
    if (getCookie("linksUnderline",'false') === "false") { //This checks if the links are underlined. The code will get the cookies, and change the links' styles accordingly.
        //Links are not underlined
        linksUnderline = false;
        for (let i = 0; i < links.length; i++) {
            links[i].style.textDecoration = 'none';
        }
    } else {
        //Links are underlined
        linksUnderline = true;
        for (let i = 0; i < links.length; i++) {
            links[i].style.textDecoration = 'underline';
        }
    }
    if (getCookie("textFont") === "Arial") { //This checks if the text font is Arial. The code will get the cookies, and change the font of the website.
        //Text font is Arial
        textFont = "Arial";
        document.body.style.fontFamily = "Arial";
    } else {
        //Text font is not Arial
        textFont = "Poppins";
        document.body.style.fontFamily = "Poppins";
    }
    textSizeMultiplier = getCookie("textSize",'1'); //This gets the text size multiplier from the cookies.
    r.style.fontSize = getCookie("textSize",'1') + "rem"; //This changes the text size of the website.
}