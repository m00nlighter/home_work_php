# GEOCODING

Simple geocode address
```sh
$g = New Geocoding();
$g->geocode("Berlin");
$result = $g->get("formatted_address");
echo $result;
```
Add api key, language, and filter with components
```sh
$g = New Geocoding();
$g->json();
$g->country("Germany");
$g->lang("de");
$g->apiKey("AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ");
$g->geocode("Berlin");
$result = $g->get("formatted_address");
echo $result;
```
Config for all geocoding types
```sh
$g = New Geocoding();
$g->json();//response format 
$g->xml();//response format
$g->apiKey("AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ");//apikey
$g->lang("de");//lang
echo $result;
```
address geocode
```sh
$g = New Geocoding();
$g->country("Germany");
$g->locality("Berlin");
$g->administrative_area("Berlin");
$g->postal_code("13629");
$g->route("harriestrasse");
$g->region("");//region code
$g->bounds("bounds");
$result = $g->geocode("harriesstrasse 13");
$g->get("geometry.locality.lat");
echo $result;
```
place geocode
```sh
$g = New Geocoding();
$g->place("place_id");
$result = $g->get("forrmated_address");
echo $result;
```
reverse geocoding
```sh
$g = New Geocoding();
$g->reverse("40.7142205,-73.9612903");
$g->result_type('street_address'); coma separated string or array
$g->location_type("ROOFTOP"); coma separated string or array
$result = $g->get("forrmated_address");
echo $result;
```
navigation between results
```sh
$g = New Geocoding();
$g->geocode("Berlin");
$result = $g->get("forrmated_address");
echo $result;
$g->next();//next prev first last
$result = $g->get("forrmated_address");
echo $result;
```