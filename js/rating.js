var displayRating = '';
var currentId = '';

function updateRating(obj, rating)
{
var id = obj.title;
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
var currentRating =obj.title;
	var totalRating = rating;
	currentId = idName;

var url="ajax.php?action=vote_file&id="+currentId+"&vote_num="+currentRating;

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){ alert(t.responseText);}
 }); 

}


function changeover(obj, rating) {
	
	var imageName = obj.src;
	var id = obj.title;
	var index = imageName.lastIndexOf('/');
	var filename = imageName.substring(index+1);
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
	var totalRating = rating;

	for(i=0; i<id; i++) {
		var num = i+1;
		
		if (num%2 == 0) {
			document.getElementById(idName+'_'+num).src = 'images/rating/_even1.jpg';			
		}
		else {
			document.getElementById(idName+'_'+num).src = 'images/rating/_odd1.jpg';
		}
	}

}

function changeout(obj, rating) {

	var imageName = obj.src;
	var id = obj.title;
	var index = imageName.lastIndexOf('/');
	var filename = imageName.substring(index+2);
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
	var totalRating = rating;
	
	for(i=0; i<id; i++) {
		var num = i+1;
		
		if (num%2 == 0) {
			if(i < totalRating) {
				document.getElementById(idName+'_'+num).src = 'images/rating/__even1.jpg';			
			}
			else {
				document.getElementById(idName+'_'+num).src = 'images/rating/even1.jpg';			
			}
		}
		else {
			if(i < totalRating) {
				document.getElementById(idName+'_'+num).src = 'images/rating/__odd1.jpg';			
			}
			else {
				document.getElementById(idName+'_'+num).src = 'images/rating/odd1.jpg';			
			}
		}
	}
}

function displayStars(rating, idName) {

	document.write('<center>');
	
	for(i=0; i < 10; i++ ) {
		if(i%2 ==0) {
			if(i < rating) {
				document.write('<img src="images/rating/__odd1.jpg" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+idName+')" />');
			}
			else {
				document.write('<img src="images/rating/odd1.jpg" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+idName+')" />');
			}
		}
		else {
			if(i < rating) {
				document.write('<img src="images/rating/__even1.jpg" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+idName+')" />');
			}
			else {
				document.write('<img src="images/rating/even1.jpg" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+idName+')" />');
			}
		}
	}

	if (displayRating == '') {
		document.write('<br /><div class="ratingText" id="'+idName+'_showrating" >'+displayRating+'</div>');
	}
	else {
		document.write('<br /><div class="ratingText" id="'+idName+'_showrating" >'+totalRating+'</div>');
	}
	document.write('</center>');

}