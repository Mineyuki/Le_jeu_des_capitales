$(document).ready(function()
{
	function getRandomInt(max)
	{
		return Math.floor(Math.random() * Math.floor(max));
	}

	var index = 0;
	var world = [];

	function executeRequest(callback)
	{
		if(world.length === 0)
		{
			var xhr = getXMLHttpRequest();
			xhr.onreadystatechange = function() 
			{
				if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
				{
					world = JSON.parse(xhr.responseText);
					callback();
				}
			}

			xhr.open("GET", "countries-master/countries.json", true);
			xhr.send();
		}
		else
		{
			callback();
		}
	}

	function readNext()
	{
		var lengthWorld = world.length; 
		var index = getRandomInt(lengthWorld);

		document.getElementById("nameState").innerHTML = (world[index].name).official;
		document.getElementById("nameCapital").innerHTML = world[index].capital;
	}

	executeRequest(readNext);
});
