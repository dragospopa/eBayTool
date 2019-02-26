<html>
<head>

<link rel="stylesheet" type="text/css" href="ss-styles.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<title>eBay Search Results for <?php echo $query; ?></title>
<script>

var input = document.getElementById("search-txt");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Cancel the default action, if needed
  event.preventDefault();
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Trigger the button element with a click
    document.getElementById("search-btn").click();
  }
});
</script>

<script>


function search(){ 
  myAjax();
  getSearchQuery();
}


function getSearchQuery(){
    var query = "eBay Search Results for "; 
    query = query + document.getElementById("search-txt").value;
    document.getElementById("query").innerHTML = query;
}

function myAjax() {
      $.ajax({
           type: "POST",
           url: 'search.php',
           data:{query: document.getElementById("search-txt").value, currency: document.getElementById("currency").value, freeShippingOnly: document.getElementById("freeShippingOnly").checked, maxPrice: document.getElementById("maxPrice").value, minPrice: document.getElementById("minPrice").value },
           success:function(html) {
             document.getElementById("search-results").innerHTML = html;
           }
      });
      console.log(document.querySelector('.freeShippingOnly:checked').value);
 }

</script>
</head>

<body>

<div class="container">

<input name="search-txt" type="text" maxlength="512" id="search-txt"/>
<button id="search-btn" onclick="search()">Search and add to db</button>
/
<label for="minPrice">Min Price</label>
<select id="minPrice">
  <option value="0">Any</option>
  <option value="25">25</option>
  <option value="100">100</option>
  <option value="250">250</option>
  <option value="500">500</option>
  <option value="1000">1000</option>
</select>
/
<label for="maxPrice">Max Price</label>
<select id="maxPrice">
  <option value="999999999">Any</option>
  <option value="25">25</option>
  <option value="250">250</option>
  <option value="500">500</option>
  <option value="1000">1000</option>
  <option value="2500">2500</option>
  <option value="5000">5000</option>
</select>
/
<label for="currency">Currency</label>
<select id="currency">
  <option value="GBP">GBP</option>
  <option value="USD">USD</option>
  <option value="EUR">EUR</option>
  <option value="AUD">AUD</option>
  <option value="CAD">CAD</option>
  <option value="CHF">CHF</option>
  <option value="CNY">CNY</option>
  <option value="HKD">HKD</option>
  <option value="INR">INR</option>
  <option value="MYR">MYR</option>
  <option value="PHP">PHP</option>
  <option value="PLN">PLN</option>
  <option value="SEK">SEK</option>
  <option value="SGD">SGD</option>
  <option value="TWD">TWD</option>
</select>
/
<label for="FreeShippingOnly">Free Shipping Only</label>
<input type="checkbox" name="vehicle2" value="FreeShippingOnly" id="freeShippingOnly">
/


<h1 id="query"></h1>
<table id="search-results">
</table>
</div>

</body>
</html>
