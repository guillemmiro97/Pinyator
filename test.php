<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-size: 28px;
}

.header {
  background-color: #f1f1f1;
  padding: 30px;
  text-align: center;
}

#navbar {
  overflow: hidden;
  background-color: #333;
}

#navbar a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

#navbar a:hover {
  background-color: #ddd;
  color: black;
}

#navbar a.active {
  background-color: #4CAF50;
  color: white;
}

.content {
  padding: 16px;
}

.sticky {
  position: fixed;
  top: 0;
  width: 100%;
}

.sticky + .content {
  padding-top: 60px;
}

</style>
</head>
<body>


<div id="navbar">
  <a class="active" href="#home">Home</a>
  <a href="#news">News</a>
  <a href="#contact">Contact</a>
</div>


<div class="content">
  <h2>Sidebar</h2>
  <p>This sidebar is of full height (100%) and always shown.</p>
  <p>Scroll down the page to see the result.</p>
  <p>Some text to enable scrolling.. Lorem ipsum dolor sit amet, illum definitiones no quo, maluisset concludaturque et eum, altera fabulas ut quo. Atqui causae gloriatur ius te, id agam omnis evertitur eum. Affert laboramus repudiandae nec et. Inciderint efficiantur his ad. Eum no molestiae voluptatibus.</p>
  <p>Some text to enable scrolling.. Lorem ipsum dolor sit amet, illum definitiones no quo, maluisset concludaturque et eum, altera fabulas ut quo. Atqui causae gloriatur ius te, id agam omnis evertitur eum. Affert laboramus repudiandae nec et. Inciderint efficiantur his ad. Eum no molestiae voluptatibus.</p>
  <p>Some text to enable scrolling.. Lorem ipsum dolor sit amet, illum definitiones no quo, maluisset concludaturque et eum, altera fabulas ut quo. Atqui causae gloriatur ius te, id agam omnis evertitur eum. Affert laboramus repudiandae nec et. Inciderint efficiantur his ad. Eum no molestiae voluptatibus.</p>
  <p>Some text to enable scrolling.. Lorem ipsum dolor sit amet, illum definitiones no quo, maluisset concludaturque et eum, altera fabulas ut quo. Atqui causae gloriatur ius te, id agam omnis evertitur eum. Affert laboramus repudiandae nec et. Inciderint efficiantur his ad. Eum no molestiae voluptatibus.</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p><p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
  <p>Some text to enable scrolling..</p>
</div>   

<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>
</body>
</html> 
