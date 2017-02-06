/***********************************************
 * DHTML Ticker script- © Dynamic Drive (www.dynamicdrive.com)
 * This notice MUST stay intact for legal use
 * Visit http://www.dynamicdrive.com/ for this script and 100s more.
 ***********************************************/

function domticker(content, divId, divClass, delay, fadeornot) {
    this.content = content
    this.tickerid = divId //ID of master ticker div. Message is contained inside first child of ticker div
    this.delay = delay //Delay between msg change, in miliseconds.
    this.mouseoverBol = 0 //Boolean to indicate whether mouse is currently over ticker (and pause it if it is)
    this.pointer = 1
    this.opacitystring = (typeof fadeornot !== "undefined") ? "width: 96%; filter:progid:DXImageTransform.Microsoft.alpha(opacity=100); -moz-opacity: 1" : ""
    if (this.opacitystring != "") this.delay += 500 //add 1/2 sec to account for fade effect, if enabled
    this.opacitysetting = 0.2 //Opacity value when reset. Internal use.
    document.write('<div id="' + divId + '" class="' + divClass + '"><div style="' + this.opacitystring + '">' + content[0] + '</div></div>')
    var instanceOfTicker = this
    setTimeout(function () {
        instanceOfTicker.initialize()
    }, delay)
}

domticker.prototype.initialize = function () {
    var instanceOfTicker = this
    this.contentdiv = document.getElementById(this.tickerid).firstChild //div of inner content that holds the messages
    document.getElementById(this.tickerid).onmouseover = function () {
        instanceOfTicker.mouseoverBol = 1
    }
    document.getElementById(this.tickerid).onmouseout = function () {
        instanceOfTicker.mouseoverBol = 0
    }
    this.rotatemsg()
}

domticker.prototype.rotatemsg = function () {
    var instanceOfTicker = this
    if (this.mouseoverBol == 1) //if mouse is currently over ticker, do nothing (pause it)
        setTimeout(function () {
            instanceOfTicker.rotatemsg()
        }, 100)
    else {
        this.fadetransition("reset") //FADE EFFECT- RESET OPACITY
        this.contentdiv.innerHTML = this.content[this.pointer]
        this.fadetimer1 = setInterval(function () {
            instanceOfTicker.fadetransition('up', 'fadetimer1')
        }, 100) //FADE EFFECT- PLAY IT
        this.pointer = (this.pointer < this.content.length - 1) ? this.pointer + 1 : 0
        setTimeout(function () {
            instanceOfTicker.rotatemsg()
        }, this.delay) //update container
    }
}

// -------------------------------------------------------------------
// fadetransition()- cross browser fade method for IE5.5+ and Mozilla/Firefox
// -------------------------------------------------------------------

domticker.prototype.fadetransition = function (fadetype, timerid) {
    var contentdiv = this.contentdiv
    if (fadetype == "reset")
        this.opacitysetting = 0.2
    if (contentdiv.filters && contentdiv.filters[0]) {
        if (typeof contentdiv.filters[0].opacity == "number") //IE6+
            contentdiv.filters[0].opacity = this.opacitysetting * 100
        else //IE 5.5
            contentdiv.style.filter = "alpha(opacity=" + this.opacitysetting * 100 + ")"
    }
    else if (typeof contentdiv.style.MozOpacity != "undefined" && this.opacitystring != "") {
        contentdiv.style.MozOpacity = this.opacitysetting
    }
    else
        this.opacitysetting = 1
    if (fadetype == "up")
        this.opacitysetting += 0.2
    if (fadetype == "up" && this.opacitysetting >= 1)
        clearInterval(this[timerid])
}
