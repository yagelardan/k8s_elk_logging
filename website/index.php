<html>
<title>logs website</title>
	<body>
<?php
//$url = 'http://elasticsearch:9200/_search'; 
$url = 'elasticsearch-service:9200/_search';  //FOR KUBERNETES

//Initiate cURL.
$ch = curl_init($url);


//The JSON data.
$jsonData = array(
    'size' => 0,
    'aggs' => [
      'unique' => [
        'terms' => [
          'field' => 'host.keyword',
           'size' => 10000
   		]
	     ]
	]
       );

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
#echo $jsonDataEncoded;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); #curl_exec doesnt print output

# curl_getinfo($ch);

//Execute the request
$json = curl_exec($ch);
$info = curl_getinfo($ch);

curl_close($ch);

$json_decoded = json_decode($json, 1);

$hosts_arr = [];
foreach($json_decoded['aggregations']['unique']['buckets'] as $host) {
	    $hosts_arr[] = $host['key'];
}

#print_r($hosts_arr);
echo "HOSTS:"."<br>";
foreach($hosts_arr as $host)
{
?>
	<a href='?host=<?php echo $host;?>'><?php echo $host;?></a><br>
<?php
}
?>
<div style="float:right;">
<textarea style="width:50em; height:80%; float:left;" readonly>
<?php
print_logs("");
?>
</textarea>
</div>
<?php
function print_logs($host)
{

//$url = 'http://elasticsearch:9200/_search';
$url = 'elasticsearch-service:9200/_search';  //FOR KUBERNETES


#Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$host_log=$_GET['host'];
$jsonData = array(
'size' => 1000,
'query' => [
'term' => [
'host.keyword' => "$host_log"
]
]
);

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
	
//echo $jsonDataEncoded;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); #curl_exec doesnt print output

 #curl_getinfo($ch);

//Execute the request
$json = curl_exec($ch);
$info = curl_getinfo($ch);

curl_close($ch);
#echo $json;	
$json_decoded = json_decode($json, 1);
#print_r($json_decoded);

$hosts_arr = [];
$index=0;
#echo $json_decoded['hits']['hits']['1']['_source']['message']['0'];
$logs="";
	foreach($json_decoded['hits']['hits'] as $host) {
	 #$hosts_arr[] = $host['0'];
	   # echo $host['0']['_source']['message'] ."<br>";
	    $logs = $logs.$host['_source']['message']['0'];
	    $index++;
	}	    	
echo $logs;



}

//echo $json;








?>
	</body>
</html>

