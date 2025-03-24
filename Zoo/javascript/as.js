"use strict"; //suses strict mode

/*Cookie set*/

function setCookie(cname,cvalue,exdays) { //this function is used to set cookies.
    const d = new Date(); //gets the current date.
    d.setTime(d.getTime() + (exdays*24*60*60*1000)); //sets the expiration date to the current date plus the number of days specified.
    let expires = "expires=" + d.toUTCString(); //sets the expires date in the format required by the cookie.
    document.cookie = cname + "=" + cvalue + ";" + expires; //sets the cookie with the name, value, and expiration date.
}

function getCookie(cname,defaultValue) { //this function is used to get cookies.
    let name = cname + "="; //sets the name of the cookie.
    let decodedCookie = decodeURIComponent(document.cookie); //decodes the cookie string.
    let ca = decodedCookie.split(';'); //splits the cookie string into an array of key-value pairs.
    //console.log(decodedCookie);
    for(let i = 0; i < ca.length; i++) { //loops through the array of key-value pairs.
      let c = ca[i]; //sets the current key-value pair.
      while (c.charAt(0) == ' ') { //removes any leading spaces from the key-value pair.
        c = c.substring(1); //removes the first character from the key-value pair.
      }
      if (c.indexOf(name) == 0) { //if the cookie is found
        //console.log(c.substring(name.length, c.length));
        return c.substring(name.length, c.length); //returns the value of the cookie.

      }
    }
    return defaultValue; //else return the default value specified.
}

function checkCookie(type) { //this function is used to check if a cookie is set.
    let inf = getCookie(type); //gets the cookie with the specified type.
    if (inf != "") { //if the cookie is set
     alert('The cookies are properly stored. (value is ' + inf + ' )'); //displays a message with the value of the cookie.
    } else { //else alert the user that the cookie is not set.
      alert('Cookies are not set');
    }
}

var textSizeMultiplier = getCookie("textSize",1); //gets the cookie with the name "textSize" and a default value of 1.
var textFont = getCookie("textFont", "Poppins"); //default to Poppinsvar r = document.querySelector(':root'); //gets the root element of the document.
var linksUnderline = getCookie("linksUnderline",false); //gets the cookie with the name "linksUnderline" and a default value of false.
var links = r.getElementsByTagName('a'); //gets all the links in the document.
var highContrast = getCookie("highContrast",false); //gets the cookie with the name "highContrast" and a default value of false.

if (getCookie('cookieConsent',false) === 'true') { //if the cookie consent is set to true, i.e. consent is being given by the user already
    document.addEventListener("DOMContentLoaded", function() { //when the document is loaded
        var cookieOverlay = document.getElementById('cookie-overlay'); //gets the cookie overlay element.
        cookieOverlay.style.display = 'none'; //hides the cookie overlay.
    });
} else { //else i.e. the user has not given consent yet
    document.addEventListener("DOMContentLoaded", function() { //when the document is loaded
        var cookieOverlay = document.getElementById('cookie-overlay'); //gets the cookie overlay element.
        cookieOverlay.style.display = 'flex'; //shows the cookie overlay.
    });
}

function closeCookieConsent() { //this function is used to close the cookie consent overlay.
    setCookie('cookieConsent','true',999999999); //sets the cookie with the name "cookieConsent" to true, and a lifetime of 999999999 days.
    var cookieOverlay = document.getElementById('cookie-overlay'); //gets the cookie overlay element.
    cookieOverlay.style.display = 'none'; //hides the cookie overlay.
    /* Creating cookies */
    document.cookie = "linksUnderline=false"; //sets the cookie with the name "linksUnderline" to false.
    document.cookie = "highContrast=false"; //sets the cookie with the name "highContrast" to false.
    document.cookie = "textSize=1"; //sets the cookie with the name "textSize" to 1.
    document.cookie = "textFont=Poppins"; //sets the cookie with the name "textFont" to "PT Sans".
}

//for debugging
//document.cookie = "cookieConsent=false";


function increaseTextSize() { //this function is used to increase the text size.
    textSizeMultiplier = textSizeMultiplier - 0.25;
    textSizeMultiplier = textSizeMultiplier + 0.5;
    if (textSizeMultiplier > 3) { //make sure that the text size multiplier is not greater than 3.
        textSizeMultiplier = 3;
    }
    r.style.fontSize = textSizeMultiplier + "rem"; //sets the font size of the root element to the current text size multiplier.
    setCookie("textSize", textSizeMultiplier, 10); //sets the cookie with the name "textSize" to the current text size multiplier, and a lifetime of 10 days.
    //checkCookie('textSize');
}

function decreaseTextSize() { //this function is used to decrease the text size.
    textSizeMultiplier = textSizeMultiplier - 0.25;
    if (textSizeMultiplier < 1) { //make sure that the text size multiplier is not less than 1.
        textSizeMultiplier = 1;
    }
    //console.log(textSizeMultiplier);
    var r = document.querySelector(':root'); //gets the root element of the document.
    r.style.fontSize = textSizeMultiplier + "rem"; //sets the font size of the root element to the current text size multiplier.
    setCookie("textSize", textSizeMultiplier, 10); //sets the cookie with the name "textSize" to the current text size multiplier, and a lifetime of 10 days.
}

function changeArial(){ //this function is used to change the font to Arial.
    if (textFont === "Poppins"){ //checks if the current font is PT Sans. If it is PT Sans then change it to Arial.
        textFont = "Arial"; 
        document.body.style.fontFamily = "Arial";
    } else{ //if the current font is Arial then change it to PT Sans.
        textFont = "Poppins";
        document.body.style.fontFamily = "Poppins";
    }
    setCookie("textFont", textFont, 10); //sets the cookie with the name "textFont" to the current text font, and a lifetime of 10 days.
}

function underlineLinks(){ //this function is used to underline links.
    if (linksUnderline === false){ //checks if the links are not underlined. If they are not underlined then underline them.
        linksUnderline = true; //sets the linksUnderline variable to true.
        setCookie("linksUnderline", 'true', 10); //sets the cookie with the name "linksUnderline" to true, and a lifetime of 10 days.
        console.log('on'); //logs a message to the console to indicate that the links are now underlined.
        console.log(getCookie('linksUnderline',"Can't get")); //logs the value of the cookie with the name "linksUnderline" to the console. If the cookie can't be retrieved then logs "Can't get".
        for (let i = 0; i<links.length;i++) { //loops through all the links in the document.
            links[i].style.textDecoration = 'underline'; //sets the text decoration of the current link to underline.
        } 
        
    } else{ //if the links are underlined then don't underline them.
        linksUnderline = false; //sets the linksUnderline variable to false.
        setCookie("linksUnderline", 'false', 10); //sets the cookie with the name "linksUnderline" to false, and a lifetime of 10 days.
        console.log('off'); //logs a message to the console to indicate that the links are now not underlined.
        console.log(getCookie('linksUnderline',"Can't get")); //logs the value of the cookie with the name "linksUnderline" to the console. If the cookie can't be retrieved then logs "Can't get".
        for (let i = 0; i<links.length;i++) { //loops through all the links in the document.
            links[i].style.textDecoration = 'none'; //sets the text decoration of the current link to none.
        }
        
    }

}

function hContrast(){ //this function is used to toggle the high contrast mode.
    //console.log(highContrast);
    if (highContrast === false){ //checks if the high contrast mode is off. If it is off then turn it on.
        highContrast = true; //sets the highContrast variable to true.
        /* These are variables to change when turning on high contrast mode */
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
        setCookie("highContrast", 'true', 10); //sets the cookie with the name "highContrast" to true, and a lifetime of 10 days.
    } else { //if the high contrast mode is on then turn it off.
        highContrast = false; //sets the highContrast variable to false.
        /* These are variables to change when turning off high contrast mode */
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
        setCookie("highContrast", 'false', 10); //sets the cookie with the name "highContrast" to false, and a lifetime of 10 days.
    }

}
function asReset(){ //this function is used to reset the accessibility settings.   
    textSizeMultiplier = 1; //sets the textSizeMultiplier variable to 1 (i.e. 1rem).
    textFont = "Poppins"; //sets the textFont variable to "Poppins".
    linksUnderline = false; //sets the linksUnderline variable to false.
    highContrast = false; //sets the highContrast variable to false.
    r.style.fontSize = "1rem"; //sets the font size to 1rem.
    document.body.style.fontFamily = "Poppins"; //sets the font family to "Poppins".
    for (let i = 0; i<links.length;i++) { //for each link in the links array.
        links[i].style.textDecoration = 'none'; //sets the text decoration of the link to none.
    }
    /* These are variables to change when turning off high contrast mode */
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
    r.style.setProperty('--a-clicked', '#0fbfe4');
    r.style.setProperty('--a-decoration', 'none');
    r.style.setProperty('--hover', '#555');
    r.style.setProperty('--table-border', '#000000');
    setCookie("textSize", '1', 10); //sets the cookie with the name "textSize" to 1, and a lifetime of 10 days.
    setCookie("textFont", 'Poppins', 10); //sets the cookie with the name "textFont" to "Poppins", and a lifetime of 10 days.
    setCookie("linksUnderline", 'false', 10); //sets the cookie with the name "linksUnderline" to false, and a lifetime of 10 days.
    setCookie("highContrast", 'false', 10); //sets the cookie with the name "highContrast" to false, and a lifetime of 10 days.
}

