<?PHP
include_once('settings.php');
// load functions
include_once('func_generic.php');
include_once('func_post_data.php');
include_once('func_parse_obs.php');

//3. GetObservation
echo "GetObservation START <br/>";
//retrive url back from session variable
session_start();
$url_back = $_SESSION['url'];

if ($_POST['sPoint']){	
	$sPoint = $_POST['sPoint'];
	$sDetails= explode (',',$sPoint);
	
	$getOBSoffer1 = $sDetails[1];
	$getOBSpropS1 =  strtolower($getOBSoffer1);
	$getFOIs = $sDetails[0];
	$sPos = $sDetails[2];
	
	$getOBSbasic1='<?xml version="1.0" encoding="UTF-8"?><GetObservation xmlns="http://www.opengis.net/sos/1.0"  xmlns:ows="http://www.opengis.net/ows/1.1"  xmlns:gml="http://www.opengis.net/gml"   xmlns:ogc="http://www.opengis.net/ogc"  xmlns:om="http://www.opengis.net/om/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opengis.net/sos/1.0 http://schemas.opengis.net/sos/1.0.0/sosGetObservation.xsd" service="SOS" version="1.0.0" srsName="urn:ogc:def:crs:EPSG:4326"> <offering>';
	//$getOBSoffer1 = 'GAUGE_HEIGHT';
	$getOBSproperty = '</offering> <observedProperty>urn:ogc:def:phenomenon:OGC:1.0.30:';
	//$getOBSpropS1 = 'waterlevel';
	$getOBSbasic2 = '</observedProperty> <featureOfInterest><ObjectID>';
	//$getFOIs = 'foi_0911';
	$getOBSbasic3 = '</ObjectID> </featureOfInterest> <responseFormat>text/xml;subtype=&quot;om/1.0.0&quot;</responseFormat> </GetObservation>';
	
	$getOBSbasic = $getOBSbasic1.''.$getOBSoffer1.''.$getOBSproperty.''.$getOBSpropS1.''.$getOBSbasic2.''.$getFOIs.''.$getOBSbasic3;
	
	$outputGetObs = post_data($url_back, $getOBSbasic);
	
	if($outputGetObs){
		$ObsOutput = parse_obs($outputGetObs);
		// uom unit of measurement
		$dUom = ($ObsOutput[0]['uom']);
		$dValues = ($ObsOutput[0]['values']);
		$dValues_1 = explode (';',$dValues);
		//print_r($dValues_1);
		echo "$getOBSpropS1 sensor data located at $sPos";
		echo "<table border='1'><tr><td>Timestamp</td><td>FoI</td><td>value in $dUom</td></tr>";
		for($i=0; $i<(sizeof($dValues_1)-1); $i++){
			$finalData = explode(',',$dValues_1[$i]);
			$reading = round($finalData[2],3);
			
			echo "<tr><td>$finalData[0]</td>
			<td>$finalData[1]</td>
			<td>$reading</td>
			</tr>";
		}
		echo "</table>";
		
		
	}
	else{
		echo $error2;
	}
	
		$xml_file = 'getObsResp1.xml';
		$fh = fopen($xml_file, 'w') or die();
		fwrite($fh, $outputGetObs);
		fclose($fh);
	
}
else{
echo $error1;
}
echo "<br/> GetObservation END <br/>";
/*
// FOI by time
$1 = '<?xml version="1.0" encoding="UTF-8"?><GetObservation xmlns="http://www.opengis.net/sos/1.0"  xmlns:ows="http://www.opengis.net/ows/1.1"  xmlns:gml="http://www.opengis.net/gml"   xmlns:ogc="http://www.opengis.net/ogc"  xmlns:om="http://www.opengis.net/om/1.0"   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xsi:schemaLocation="http://www.opengis.net/sos/1.0  http://schemas.opengis.net/sos/1.0.0/sosGetObservation.xsd" service="SOS" version="1.0.0" srsName="urn:ogc:def:crs:EPSG:4326"> <offering>';
$2 = 'TEMPERATURE';
$3 = '</offering> <eventTime>    <ogc:TM_During> <ogc:PropertyName>om:samplingTime</ogc:PropertyName>      <gml:TimePeriod> <gml:beginPosition>';
$4 = '2009-08-27T02:25:47.961+05:30';
$5 = '</gml:beginPosition>        <gml:endPosition>'; 
$6 = '2009-11-07T20:57:16.572+05:30'; 
$7 = '</gml:endPosition>       </gml:TimePeriod> </ogc:TM_During> </eventTime><observedProperty>urn:ogc:def:phenomenon:OGC:1.0.30:';
$8 = 'temperature';
$9 = '</observedProperty>  <responseFormat>text/xml;subtype=&quot;om/1.0.0&quot;</responseFormat> </GetObservation>';

*/
?>