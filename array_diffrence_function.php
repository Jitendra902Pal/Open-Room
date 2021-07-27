function array_diff_assoc_recursive($array1, $array2)
{
	foreach($array1 as $key => $value)
	{
		if(is_array($value))
		{
			if(!isset($array2[$key]))
			{
				$difference[$key] = $value;
			}
			elseif(!is_array($array2[$key]))
			{
				$difference[$key] = $value;
			}
			else
			{
				$new_diff = array_diff_assoc_recursive($value, $array2[$key]);
				if($new_diff != FALSE)
				{
					$difference[$key] = $new_diff;
				}
			}
		}
		elseif(!isset($array2[$key]) || $array2[$key] != $value)
		{
			$difference[$key] = $value;
		}
	}
	return !isset($difference) ? 0 : $difference;
};
 $b11 = '[{"ID":"IMP10697","GR No":"","BOE No":"4642541","Consignee Name":"GATES INDIA P.LTD.","Consignor Name":"GATES INDIA P.LTD.","Size":"20","Rate 1":"11000","Rate 2":"0","To Station":"LALRU","From Station":"LUDHIANA"},{"ID":"IMP10698","GR No":"IMP10698","BOE No":"4642541","Consignee Name":"GATES INDIA P.LTD.","Consignor Name":"GATES INDIA P.LTD.","Size":"20","Rate 1":"11000","Rate 2":"0","To Station":"LALRU","From Station":"LUDHIANA"}]';
 $b1 = json_decode($b11, true) ;
 $b22 ='[{"ID":"IMP10697","GR No":"","BOE No":"4642541","Consignee Name":"GATES INDIA P.LTD.","Consignor Name":"GATES INDIA P.LTD.","Size":"20","Rate 1":"9000","Rate 2":"0","To Station":"LALRU","From Station":"LUDHIANA"},{"ID":"IMP10698","GR No":"IMP10698","BOE No":"4642541","Consignee Name":"GATES INDIA P.LTD.","Consignor Name":"GATES INDIA P.LTD.","Size":"20","Rate 1":"9000","Rate 2":"0","To Station":"LALRU","From Station":"LUDHIANA"}]';
 $b2 = json_decode($b22, true);
 $a1 = array_column($b1, null, 'ID');
 $a2 = array_column($b2, null, 'ID');
 $new_array1 = array_diff_assoc_recursive($a1, $a2);
