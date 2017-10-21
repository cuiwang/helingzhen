var icon = document.getElementById('music_icon');
var audio = null;
function switchsound()
{
	if (music_url != ''){
		if (audio == null)
		{
			audio = document.createElement('audio');
			audio.id = 'bgsound';
			audio.src = music_url;
			audio.loop = 'loop';
			document.body.appendChild(audio);
		}
		
		if (audio.paused)
		{
			audio.play();
			icon.setAttribute("play","stop");
			icon.setAttribute("class","musicAnima");
		}
		else
		{
			audio.pause();
			icon.setAttribute("play","on");
			icon.removeAttribute("class");
		}
	}
}

function stopsound()
{
	if (audio != null)
	{
		audio.pause();
	}
	icon.setAttribute("play","on");
}