
var api_key = "1f6a01fb1fcd5b731b2c5c43d93d6bf0";

function initialize () {
            document.getElementById("dp").innerHTML = " ";
	    document.getElementById("images").innerHTML = " ";
	    document.getElementById("output").innerHTML = " ";
	    document.getElementById("heading").innerHTML = " ";
	    document.getElementById("similar").innerHTML = " ";
	    document.getElementById("title").innerHTML = " ";


}
function sendRequest () {
    initialize();
    var xhr = new XMLHttpRequest();
    var method = "artist.getinfo";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
	    var name = json.artist.name;
	    var url = json.artist.url;
	    var biopub = json.artist.bio.published +"<br/>";
	    var biocont =  json.artist.bio.content + "<br/>" ;
	    var im = json.artist.image[2]["#text"];
	    var imag = new Image();
            imag.src = im;
	    document.getElementById("dp").appendChild(imag);
            document.getElementById("output").innerHTML = "<pre><b>Name:</b>" + name + "</pre>\n <b>Url:</b> <a href="+url+">"+url+"</a><br/>"+"<br/><b>Published Date:</b>"+biopub+"<br/> <b>Biography: </b>  "+biocont+"\n";
	    topAlbums();
	    similarArtists();
	    
        }
    };
    xhr.send(null);
}


function topAlbums () {
    var xhr1 = new XMLHttpRequest();
    var method = "artist.gettopalbums";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr1.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr1.setRequestHeader("Accept","application/json");
    xhr1.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
	    var I=0;
	    for (I=0; I<json.topalbums.album.length; I++)
	{
	     document.getElementById("title").innerHTML = "<b>Top Albums Of Your Artist:</b>"
	     document.getElementById("images").innerHTML += "<pre><b> Album</b>" +[I+1]+":"+json.topalbums.album[I].name + "<br/> <img src ="+json.topalbums.album[I].image[2]['#text']+"></img>"+"<br/><br/>";
	     
	}
       }
    };
    xhr1.send(null);
}

function similarArtists () {
    var xhr2 = new XMLHttpRequest();
    var method = "artist.getsimilar";
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr2.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr2.setRequestHeader("Accept","application/json");
    xhr2.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
	    var I=0;;
	    for (I=0; I<json.similarartists.artist.length;I++)
	{    document.getElementById("heading").innerHTML = "<pre><b> SIMILAR ARTISTS:</b><br/>";
	     document.getElementById("similar").innerHTML += "<pre><b> Artist</b>"+[I+1]+":" + json.similarartists.artist[I].name + "</pre>";

	     
	}
	    
            
	    
            
        }
    };
    xhr2.send(null);
}

