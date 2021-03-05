// takes latest text news from site www.kartoteka.ru
function getNews()
{
	var xhr = new XMLHttpRequest();
	xhr.addEventListener( "load", function( data )
	{
		if ( data.currentTarget.readyState == 4 && data.currentTarget.status == 200 )
		{
			var parser = new DOMParser();
			var responseDoc = parser.parseFromString( data.currentTarget.responseText, "text/html" );
			var newsText = responseDoc.getElementsByClassName( "image_block_no_image" )[0].innerText;
			var lines = newsText.split(".");
			var firstSentence = "";
			// can takes parameters from config also
			var checkWords = [" Требования", " Уставный", " Срок"];
			lines.forEach( function( entry )
			{
				if ( checkWords.some( substring => entry.includes(substring) ) )
				{
					return;
				}
				else
				{
					firstSentence += entry;
				}
			});
			document.getElementById("kartotekaNews").innerHTML = firstSentence += ".";
		}
	}, false );
	xhr.open( 'GET', 'https://www.kartoteka.ru', true ),
	xhr.send();
}

// demo for showTime function, can use on site www.kartoteka.ru via console
function getShowTime()
{
	var phone = document.getElementsByClassName('kart-phone')[0];
	var dateTimeSpan = document.createElement('span')
	phone.appendChild(dateTimeSpan);
	var dt = new Date();
	function showTime(phone, dt, dateTimeSpan)
	{
		dt.setSeconds( dt.getSeconds() + 1 );
		var h = (dt.getHours() < 10 ? "0" : "") + dt.getHours();
		var m = (dt.getMinutes() < 10 ? "0" : "") + dt.getMinutes();
		var s = (dt.getSeconds() < 10 ? "0" : "") + dt.getSeconds();
		var timeString = h + ':' + m + ':' + s;
		dateTimeSpan.innerHTML = timeString;
		setTimeout(function() {showTime(phone, dt, dateTimeSpan);}, 1000);
	}
	showTime(phone, dt, dateTimeSpan);
}