var hvpembedbuttons = document.getElementsByClassName('mobileautoembed');
for (var i = 0; i < hvpembedbuttons.length; i++) {
    hvpembedbuttons[i].addEventListener("click", function() {
        var id = this.getAttribute('data-ref');
        this.style.display = 'none';
        var hvpembednode = document.getElementById('hvpe' + id );
        hvpembednode.height = '50';
        hvpembednode.src = '/mod/hvp/embed.php?id=' + id;
        hvpembednode.addEventListener('load', function(){
            this.className = this.className.replace(/\bmobiledelay\b/g, "");
        }, true);
    });
}

var style = document.createElement('style');
style.type = 'text/css';
style.innerHTML = '.hvpautoembed a img{display:none} .section .activity.hvpautoembed .contentafterlink{margin-left:0}';
style.innerHTML += '.hvpautoembed .mobiledelay{background:url("/mod/hvp/library/images/throbber.gif") no-repeat center #e2e2e2';
document.getElementsByTagName('head')[0].appendChild(style);