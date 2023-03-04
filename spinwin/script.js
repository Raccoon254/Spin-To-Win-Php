function myfunction(){
    var x = 1024; // min value
    var y = 9999; // max value
    var deg = Math.floor(Math.random() * (x - y)) + y;
    var box = document.getElementById('box');
    var movies = box.getElementsByTagName('span');
    var selectedMovie = movies[Math.floor(deg / 45) % 8];
    box.style.transform = "rotate("+deg+"deg)";
    var element = document.getElementById('mainbox');
    element.classList.remove('animate');
    setTimeout(function(){
        element.classList.add('animate');
        var selected = document.getElementsByClassName('selected');
        for (var i = 0; i < selected.length; i++) {
            selected[i].classList.remove('selected');
        }
        selectedMovie.classList.add('selected');
        document.getElementById('result').innerHTML = selectedMovie.innerHTML;
        var blinkInterval = setInterval(function() {
            selectedMovie.classList.toggle('blink');
        }, 500);
        setTimeout(function() {
            clearInterval(blinkInterval);
            selectedMovie.classList.remove('blink');
        }, 5000);
    }, 5000); //5000 = 5 second
}
