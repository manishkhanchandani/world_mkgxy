//add new push data
http://world.mkgalaxy.com/push/push_new.php?uid=1&module=1&push_value=z&MM_insert=form1
http://world.mkgalaxy.com/push/push_new.php?module=1&MM_insert=form1&uid=1&push_value=z

//add new push ref data
http://world.mkgalaxy.com/push/push_ref_new.php?push_id=6&push_key=y&reference=1&MM_insert=form1
http://world.mkgalaxy.com/push/push_ref_new.php?push_id=6&push_key=y&reference=2&MM_insert=form1

http://world.mkgalaxy.com/push/push_ref_new_multiple.php?MM_insert=form1&push_id=6&push_details[key]=value&push_details[key1]=value1

//edit push data
http://world.mkgalaxy.com/push/push_edit.php?uid=2&module=1&push_value=z&push_id=6&MM_update=form1

//edit push ref data
delete first and
then add new


//delete push data
http://world.mkgalaxy.com/push/push_delete.php?push_id=2


//delete push ref data
http://world.mkgalaxy.com/push/push_ref_delete.php?push_id=2


search
http://world.mkgalaxy.com/push/push_search.php?k=y&v=1&module=1

http://world.mkgalaxy.com/push/push_search.php?k[0]=my_gender&v[0]=male&module=4&k[1]=looking_for_female&v[1]=true
http://world.mkgalaxy.com/push/push_search.php?k=my_gender&v=male&module=4
http://world.mkgalaxy.com/push/push_search.php?k[]=my_gender&v[]=male&module=4&k[]=looking_for_female&v[]=true
